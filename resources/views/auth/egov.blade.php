<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>
<form name="OAuthForm" action="{{$authorizationurl}}" method="get" />
<input type="hidden" name="response_type" value="one_code" />
<input type="hidden" name="client_id" value="{{$clientid}}" />
<input type="hidden" name="redirect_uri" value="http://mf.local/authenticate" />
<input type="hidden" name="scope" value="{{$scope}}" />
-<input type="hidden" name="state" value="{{$state}}" />
</form>

<script type="text/javascript">
    document.OAuthForm.submit();
</script>
</body>
</html>
