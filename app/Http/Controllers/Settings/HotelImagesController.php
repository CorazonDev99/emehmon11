<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\HotelImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

use function Laravel\Prompts\error;

class HotelImagesController extends Controller
{
    public function getIndex()
    {
        return view('hotelimages.index');
    }

    public function getForm()
    {
        $roomsTypes = \DB::table('tb_room_types')->get();
        return view('hotelimages.form', ['roomTypes' => $roomsTypes]);
    }




    public function store(Request $request)
    {

        try {
            $user = auth()->user();

            $imageCount = \DB::table('tb_hotel_photos')->where('id_hotel', $user->id_hotel)->count();
            if ($imageCount >= 10) {
                return response()->json(['errors' => 'You have reached the max number of files'], 403);
            }
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'is_main' => 'nullable|boolean',
                'room_tp' => 'nullable|integer',
            ]);

            // Handle 'is_main' as a boolean value
            $mainImage = $request->has('is_main') ? filter_var($request->input('is_main'), FILTER_VALIDATE_BOOLEAN) : false;

            // Handle 'room_tp', convert "null" to actual null
            $roomType = $request->input('room_tp');
            if ($roomType === 'null') {
                $roomType = null; // Explicitly set to null
            }

            // If 'is_main' is true, set all previous images' is_main to 0
            if ($mainImage) {
                $this->updateMainImage($user->id_hotel); // Call the reusable update method
            }

            // Check if the photo is uploaded
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');

                // Generate a unique filename
                $filename = $user->id_hotel . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the image
                $image->storeAs('hotelimages', $filename, 'public');

                // Save the image data into the database
                HotelImages::create([
                    'photo' => $filename,
                    'id_hotel' => $user->id_hotel,
                    'room_tp' => $roomType,
                    'entry_by' => $user->id,
                    'is_main' => $mainImage,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Изображение успешно загружено!'], 200);
            } else {
                return response()->json(['errors' => 'No image file uploaded.'], 403);
            }
        } catch (\Exception $e) {
            // Log the error details
            Log::error('Error in store method', [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return response()->json(['errors' => 'An error occurred while uploading the image.'], 400);
        }
    }

    /**
     * Method to update all images' is_main to 0 for a specific hotel
     *
     * @param int $hotelId
     * @return void
     */
    private function updateMainImage($hotelId)
    {
        \DB::table('tb_hotel_photos')
            ->where('id_hotel', $hotelId)
            ->update(['is_main' => 0]);
    }



    public function edit($id)
    {
        $roomsTypes = \DB::table('tb_room_types')->get();
        $hotelImage = HotelImages::find($id);
        return view('hotelimages.form', ['data' => $hotelImage, 'roomTypes' => $roomsTypes]);
    }
    public function update(Request $request, $id)
    {

        $mainImage = $request->has('is_main') ? filter_var($request->input('is_main'), FILTER_VALIDATE_BOOLEAN) : false;

        $validated = $request->validate([
            'room_tp' => 'nullable|integer',
            'is_main' => 'nullable|boolean',
        ]);
        $hotelImage = HotelImages::find($id);
        if (!$hotelImage) {
            return response()->json(['error' => 'Image not found'], 404);
        }
        if ($mainImage) {
            $this->updateMainImage(auth()->user()->id_hotel);
        }
        $hotelImage->room_tp = $request->room_tp ?? null;
        $hotelImage->is_main = $request->is_main ?? 0;
        $hotelImage->save();
        return response()->json(['status' => 'success', 'message' => 'succesfully updated'], 201);
    }

    public function destroy($id)
    {
        $hotelImage = HotelImages::find($id);

        if (!$hotelImage) {
            return response()->json(['message' => 'Изображение не найдено.'], 404);
        }

        $filePath = 'public/hotelimages/' . $hotelImage->photo;

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
        $hotelImage->delete();
        return response()->json(['message' => 'Изображение успешно удалено.'], 200);
    }


    public function getData(Request $request)
    {

        $d = \DB::table('tb_hotels as h')->join('tb_hotel_photos as hp', 'hp.id_hotel', '=', 'h.id')
            ->join('tb_hoteltype as ht', 'h.hotel_type_id', '=', 'ht.id')
            ->leftJoin('tb_room_types as rt', 'hp.room_tp', '=', 'rt.id')
            ->join('tb_region as r', 'h.id_region', '=', 'r.id')
            ->select(['hp.photo', 'hp.created_at', 'hp.is_main', 'hp.id_hotel', 'r.name as region_name', 'h.name as hotel_name', 'ht.name as hoteltype', 'rt.en as roomtype', 'hp.id']);

        if (in_array(\Session::get('gid', 0) * 1, [3, 4, 9])) {
            $d = $d->where('h.id', \Session::get('hid', 0))->where('h.noshow', 0);
        }

        if ($region_id = $request->get('region_id')) $d->where('r.id', '=', $region_id);
        if ($hotel_id = $request->get('hotel_id')) $d->where('h.id', '=', $hotel_id);
        return DataTables::of($d)
            ->editColumn('roomtype', function ($row) {
                return $row->roomtype ? $row->roomtype : 'С\Р';
            })
            ->editColumn('photo', function ($row) {
                if (isset($row->photo) && $row->photo)
                    return asset('storage/hotelimages/' . $row->photo);
                return '';
            })
            ->make(true);
    }
}
