@include("admin_layouts.includes.header")
@include("admin_layouts.includes.sidebar")
@include("admin_layouts.includes.footer")
<!--begin::Page Scripts(used by this page)-->
<script src="{{asset('assets/js/pages/widgets.js')}}"></script>
<script src="{{asset('assets/js/pages/waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/pages/counterup.min.js')}}"></script>

<script>
    $('.counter').counterUp({
        delay: 10,
        time: 1500
    });
</script>