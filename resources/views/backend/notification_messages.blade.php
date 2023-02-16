
 @if(Session::has('notification_success'))
<script type="text/javascript">
  notification_msg('{{Session::get("notification_success")}}','success');     
</script>
@endif

@if(Session::has('notification_error'))
<script type="text/javascript">
  notification_msg('{{Session::get("notification_error")}}');     
</script>
@endif