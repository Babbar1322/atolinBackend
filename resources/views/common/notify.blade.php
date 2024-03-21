@if (count($errors) > 0)
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(Session::has('flash_error'))
   <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        {{ Session::get('flash_error') }}
    </div>
@endif


@if(Session::has('flash_success'))
   <div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
        {{ Session::get('flash_success') }}
    </div> 
    <script type="text/javascript">
    var axel = Math.random() + "";
    var a = axel * 10000000000000;
    document.write('<iframe src="https://9923538.fls.doubleclick.net/activityi;src=9923538;type=invmedia;cat=amepa00;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=' + a + '?" width="1" height="1" frameborder="0" style="display:none"></iframe>');
    </script>
    <noscript>
    <iframe src="https://9923538.fls.doubleclick.net/activityi;src=9923538;type=invmedia;cat=amepa00;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=1?" width="1" height="1" frameborder="0" style="display:none"></iframe>
</noscript>
@endif

@if(Session::has('flash_warning'))
   <div class="col-md-12">
       <div class="container" style="margin-top: 25px;" style="position: initial;">
           <div class="alert alert-warning">
               <button type="button" class="close" data-dismiss="alert">×</button>
               {{ Session::get('flash_warning') }}
           </div>
       </div>
   </div>
@endif

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

 <script src="{{ ('/js/jquery.min.js') }}" defer></script>

<script type="text/javascript">
       $(document).on('click', '.close', function() { 
          
             $(this).parents('div .alert').fadeOut();
        });
</script>