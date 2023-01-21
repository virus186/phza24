@extends('packages/installer::master')

@section('template_title')
    {{ trans('packages/installer::installer.requirements.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-list-ul fa-fw" aria-hidden="true"></i>
    {{ trans('packages/installer::installer.requirements.title') }}
@endsection

@section('container')

    @foreach ($requirements['requirements'] as $type => $requirement)
        <ul class="list">
            <li class="list__item list__title {{ $phpSupportInfo['supported'] ? 'success' : 'error' }}">
                <strong>{{ ucfirst($type) }}</strong>
                @if ($type === 'php')
                    <strong>
                        <small>
                            (version {{ $phpSupportInfo['minimum'] }} required)
                        </small>
                    </strong>
                    <span class="float-right">
                        <strong>
                            {{ $phpSupportInfo['current'] }}
                        </strong>
                        <i class="fa fa-fw fa-{{ $phpSupportInfo['supported'] ? 'check-circle-o' : 'exclamation-circle' }} row-icon"
                           aria-hidden="true"></i>
                    </span>
                @endif
            </li>
            @foreach ($requirements['requirements'][$type] as $extension => $enabled)
                <li class="list__item {{ $enabled ? 'success' : 'error' }}">
                    {{ $type !== 'permissions' ? ucfirst($extension) : $extension }}
                    <i class="fa fa-fw fa-{{ $enabled ? 'check-circle-o' : 'exclamation-circle' }} row-icon"
                       aria-hidden="true"></i>
                </li>
            @endforeach
        </ul>
    @endforeach

    @if (!isset($requirements['errors']) && $phpSupportInfo['supported'] )
        <div class="buttons">
            <a class="button" href="{{ URL::signedRoute('installers.environment', [], \Carbon\Carbon::now()->addMinutes(30)) }}">
                {{ trans('packages/installer::installer.permissions.next') }}
                <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
            </a>
        </div>
    @endif

@endsection
