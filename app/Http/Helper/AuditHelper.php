<?php

namespace App\Http\Helper;

use App\Services\AuditEvent;
use App\Services\ClickhouseService;
use Carbon\Carbon;

class AuditHelper
{

    public static function logAuditEvent(string $eventType, int $entityId, string $entityType, array $changes)
    {
        AuditEvent::add($eventType, $entityId, $entityType, $changes);
    }

    public static function getAuditLogs(int $entityId): array
    {
        $clickhouse = app(ClickhouseService::class);

        $query = "
            SELECT
                event_type,
                hotel_name,
                user_name,
                entity_id,
                entity_type,
                event_time,
                changes
            FROM emehmon.audit_events
            WHERE entity_id = :entity_id
            ORDER BY event_time DESC
        ";

        try {
            $auditLogs = $clickhouse->select($query, ['entity_id' => $entityId]);

            return array_map(function ($log) {
                $log['changes'] = self::formatChanges($log['changes'], $log['event_type']);
                $log['event_time'] = Carbon::parse($log['event_time'])->format('d.m.Y H:i');
                return $log;
            }, $auditLogs);
        } catch (\Exception $e) {
            \Log::error('ClickHouse query error: ' . $e->getMessage());
            return [];
        }
    }






    private static function formatChanges(string $changes, string $event_type): string
    {
        try {
            $changesObj = json_decode($changes, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Ошибка декодирования JSON', [
                    'changes' => $changes,
                    'error' => json_last_error_msg()
                ]);
                return 'Ошибка при обработке изменений';
            }

            if (!is_array($changesObj) || empty($changesObj)) {
                return 'Изменения не указаны';
            }

            $paymentStatuses = [
                0 => 'Не оплачен',
                1 => 'Оплачен частично',
                2 => 'Оплачен полностью',
                3 => 'Не оплачен',
            ];

            $message = '';

            switch ($event_type) {
                case 'Присвоил тег':
                    $message = "Присвоен тег: {$changesObj['tag']}";
                    break;

                case 'Удалить тег':
                    $message = "Удален тег: {$changesObj['tag']}";
                    break;

                case 'Продление визы':
                    $newDateVisaOn = Carbon::parse($changesObj['new']['dateVisaOn'])->format('d.m.Y H:i');
                    $newDateVisaOff = Carbon::parse($changesObj['new']['dateVisaOff'])->format('d.m.Y H:i');
                    $message = "Продлена виза с {$newDateVisaOn} до {$newDateVisaOff}";
                    break;

                case 'Обновление платежа':
                    $oldPayed = $paymentStatuses[$changesObj['old']['payed']] ?? 'Не оплачено';
                    $newPayed = $paymentStatuses[$changesObj['new']['payed']] ?? 'Не оплачено';

                    $oldAmount = $changesObj['old']['amount'] !== null
                        ? number_format((float)$changesObj['old']['amount'], 2, ',', ' ')
                        : '0,00';
                    $newAmount = $changesObj['new']['amount'] !== null
                        ? number_format((float)$changesObj['new']['amount'], 2, ',', ' ')
                        : '0,00';

                    $message = "Платежный статус: {$oldPayed} → {$newPayed}, Сумма: {$oldAmount} → {$newAmount}";
                    break;

                case 'Создание пользователя':
                    $username = $changesObj['new']['username'] ?? 'Неизвестный пользователь';
                    $email = $changesObj['new']['email'] ?? 'Нет email';
                    $message = "Создан новый пользователь: {$username} ({$email})";
                    break;

                case 'Удаление пользователя':
                    $username = $changesObj['old']['username'] ?? 'Неизвестный пользователь';
                    $email = $changesObj['old']['email'] ?? 'Нет email';
                    $message = "Удален пользователь: {$username} ({$email})";
                    break;

                default:
                    foreach ($changesObj['old'] as $field => $oldValue) {
                        $newValue = $changesObj['new'][$field] ?? null;

                        if (is_array($oldValue) && is_array($newValue)) {
                            $removed = array_diff($oldValue, $newValue);
                            $added = array_diff($newValue, $oldValue);

                            if (!empty($removed) || !empty($added)) {
                                $removedText = !empty($removed) ? "Удалены: " . implode(", ", $removed) : "";
                                $addedText = !empty($added) ? "Добавлены: " . implode(", ", $added) : "";
                                $message .= "Поле **$field** изменилось: $removedText $addedText\n";
                            }
                        } else {
                            if ($oldValue != $newValue) {
                                $message .= "Поле **$field** изменилось: '$oldValue' → '$newValue'\n";
                            }
                        }
                    }
                    break;
            }

            return $message ?: 'Изменения не указаны';
        } catch (\Exception $e) {
            \Log::error('Ошибка при обработке изменений: ' . $e->getMessage(), [
                'changes' => $changes,
                'event_type' => $event_type,
            ]);
            return 'Ошибка при обработке изменений';
        }
    }
}
