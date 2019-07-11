<header class="header">
    <div class="logo-container">
        <a href="{{route('tenant.dashboard')}}" class="logo pt-2 pt-md-0">
            @if($vc_company->logo)
                <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}" alt="Logo" />
            @else
                <img src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
            @endif
        </a>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="header-right">
        <ul class="notifications">
            <li class="open">
                @php
                    $notificacion = Illuminate\Support\Facades\DB::connection('tenant')->select("SELECT COUNT(*) AS quantity
                    FROM documents doc
                    WHERE doc.`state_type_id` = '01' AND DATEDIFF('".date("Y-m-d")."', doc.`date_of_issue`) > 3
                    ORDER BY doc.`date_of_issue` desc");
                @endphp
                @if($notificacion[0]->quantity > 0)  
                    <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-bell"></i>
                            <span class="badge">{{ $notificacion[0]->quantity }}</span>
                    </a>
                    <div class="dropdown-menu notification-menu">
                        <div class="notification-title bg-primary">Alerts</div>
                        <div class="content">
                            <ul>
                                <li>
                                    <a href="{{route('tenant.alerts.documents.index')}}" class="clearfix">
                                        <div class="image">
                                            <i class="fa fa-receipt bg-danger"></i>
                                        </div>
                                        <span class="title">Tiene comprobantes por vencer <span class="badge badge-warning"></span></span>
                                        <span class="message">Pendientes de envio a SUNAT/OSE</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </li>
        </ul>
        <span class="separator"></span>
        <div id="userbox" class="userbox">
            @php
                $path = explode('/', request()->path());
                $pos = \App\Models\Tenant\Pos::active();
            @endphp
            @if(is_null($pos))
                @if($path[0] != 'box')
                    <a class="btn btn-sm btn-warning mt-2 mr-2 text-white" href="{{route('tenant.box.index')}}">
                        <i class="fas fa-cash-register mr-1"></i> ¡Aperturar Caja!
                    </a>
                @endif
            @else
                @if(date("H:i:s") > '17:00:00')
                    <a class="btn btn-sm btn-warning mt-2 mr-2 text-white" href="{{route('tenant.box.index')}}">
                        <i class="fas fa-cash-register mr-1"></i> ¡No se olvidé cerrar su caja al finalizar el día!
                    </a>
                @endif
            @endif
            <a href="#" data-toggle="dropdown">
                <figure class="profile-picture">
                    {{-- <img src="{{asset('img/%21logged-user.jpg')}}" alt="Profile" class="rounded-circle" data-lock-picture="img/%21logged-user.jpg" /> --}}
                    <div class="border rounded-circle text-center" style="width: 25px;"><i class="fas fa-user"></i></div>
                </figure>
                <div class="profile-info" data-lock-name="{{ $vc_user->email }}" data-lock-email="{{ $vc_user->email }}">
                    <span class="name">{{ $vc_user->name }}</span>
                    <span class="role">{{ $vc_user->email }}</span>
                </div>
                <i class="fa custom-caret"></i>
            </a>
            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    <li class="divider"></li>
                    <li>
                        {{--<a role="menuitem" href="#"><i class="fas fa-user"></i> Perfil</a>--}}
                        <a role="menuitem" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>