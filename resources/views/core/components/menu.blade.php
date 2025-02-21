<nav class="navbar navbar-light navbar-expand-lg topnav-menu">

    <div class="collapse navbar-collapse justify-content-between" id="topnav-menu-content">
        <ul class="navbar-nav">
            @foreach ($menuItems as $item)
                @php
                    $hasChildren = !empty($item->children);
                @endphp

                <li class="nav-item {{ $hasChildren ? 'dropdown' : '' }}">
                    <a class="nav-link {{ $hasChildren ? 'dropdown-toggle arrow-none' : '' }}"
                       href="{{ $item->url ?? '#' }}"
                       @if ($hasChildren) role="button" id="topnav-{{ $item->menu_id }}" data-bs-toggle="dropdown" aria-expanded="false" @endif>
                        <i class="{{ $item->menu_icons }} me-2"></i> {{ $item->menu_name }}
                        @if ($hasChildren)
                            <div class="arrow-down"></div>
                        @endif
                    </a>

                    @if ($hasChildren)
                        <div class="dropdown-menu mega-dropdown-menu px-2" aria-labelledby="topnav-{{ $item->menu_id }}">
                            <div>
                                @foreach ($item->children as $child)
                                    @php
                                        $hasSubChildren = !empty($child->children);
                                    @endphp

                                    <div class="{{ $hasSubChildren ? 'dropdown-submenu' : '' }}">
                                        <a href="{{ $child->url }}" class="dropdown-item {{ $hasSubChildren ? 'dropdown-toggle' : '' }}"
                                           @if ($hasSubChildren) role="button" data-bs-toggle="dropdown" aria-expanded="false" @endif>
                                            <i class="{{ $child->menu_icons }}"></i> {{ $child->menu_name }}
                                            @if ($hasSubChildren)
                                                <div class="arrow-right"></div> <!-- Иконка для submenu -->
                                            @endif
                                        </a>

                                        @if ($hasSubChildren)
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                @foreach ($child->children as $subChild)
                                                    <li>
                                                        <a href="{{ $subChild->url }}" class="dropdown-item">
                                                            <i class="{{ $subChild->menu_icons }}"></i> {{ $subChild->menu_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="d-flex">
            <a href="/booking" class="btn btn-primary d-lg-block">
                <i class="fas fa-book"></i>
                БРОНИРОВАНИЕ
            </a>
        </div>
    </div>
</nav>
