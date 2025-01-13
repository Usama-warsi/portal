<script src="{{ asset('js/pusher.min.js') }}"></script>
<script >
  // Enable pusher logging - don't include this in production
  Pusher.logToConsole = false;

  $admin_settings = getAdminAllSetting();

  var pusher = new Pusher($admin_settings['PUSHER_APP_KEY'], {
    encrypted: true,
    cluster: $admin_settings['PUSHER_APP_CLUSTER'],
    authEndpoint: '{{route("pusher.auth")}}',
    auth: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }
  });
</script>
<script src="{{ asset('js/chatify/code.js') }}"></script>
<script>
  // Messenger global variable - 0 by default
  messenger = "{{ @$id }}";
</script>
