@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
    @else
        <!-- <div class="alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            {{ Session::get('flash_notification.message') }}
        </div> -->
        <script type="text/javascript">
        	if ("{{ Session::get('flash_notification.level') }}"=='success') {
        		ShopifyApp.flashNotice("{{ Session::get('flash_notification.message') }}");
        	} else {
        		ShopifyApp.flashError("{{ Session::get('flash_notification.message') }}");
        	}
        </script>
    @endif
@endif
@if (count($errors) > 0)
    <script type="text/javascript">
        @foreach ($errors->all() as $error)
            ShopifyApp.flashError('{{ $error }}');
        @endforeach
    </script>
@endif