<div class="col-lg-6">
    <div class="estiment_content">
        <h5>{{ $subscribeContent->title }}</h5>
        <h2>{{ $subscribeContent->subtitle }}</h2>
        <form action="" id="subscriptionForm">
            <div class="input-group">
                <input type="text" name="email" class="form-control" id="subscription_email_id"
                    placeholder="{{ __('defaultTheme.enter_email_address') }}" />
                <div class="input-group-append">
                    <button id="subscribeBtn"
                        class="input-group-text">{{ __('defaultTheme.subscribe') }}</button>
                </div>
            </div>
            <div class="message_div d-none">

            </div>

        </form>
        <p>
            @php echo $subscribeContent->description; @endphp
        </p>
    </div>
</div>