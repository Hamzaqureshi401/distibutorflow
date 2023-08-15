<script type="text/javascript">
	/** Complete Check box functionality
// Data

/**
<button type="button" 
                onclick="document.getElementById('multiple-approve').submit()" 
                class="btn btn-success btn-sm d-none confirm-btn">Confirm</button>

<input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" value=""   class="select-check-box" name="select-check-box[]" form="multiple-approve" unchecked="">

<form style="display: none" method="post" action="{{ route('Get.Pos.Sale.Deatils') }}" id="multiple-approve">
    {{ csrf_field() }}
</form>

 **/ 

// single checkbox action
$(document).ready(function() {
$('.select-check-box').change(function(){
    if ($(this).prop('checked')==true){ 
        // $(this).attr('checked','checked');
    }else if($(this).prop('checked')==false){
       // $(this).removeAttr('checked');
    }
     showbtn();
});
});
$(document).ready(function(){
   $(document).on('click', '.check-all', function () {
        $('#multiple-approve .select-check-box').attr('checked','checked');
        $('.select-check-box').bootstrapToggle('on');
        $(this).html('Uncheck All');
        $(this).addClass('uncheck-all');
        $(this).removeClass('check-all');
    })
    $(document).on('click', '.uncheck-all', function () {
       $('#multiple-approve .select-check-box').removeAttr('checked');
        $('.select-check-box').bootstrapToggle('off'); 
        $(this).html('Check All');
        $(this).addClass('check-all');
        $(this).removeClass('uncheck-all');
    })
     showbtn();
});
function showbtn(){
   checked = $(".select-check-box:checked").length;
   console.log(checked);
      if(checked != 0) {
        $('.confirm-btn').removeClass('d-none');
      }else{
        $('.confirm-btn').addClass('d-none');
      }
}
/** Complete Check box functionality end **/ 

</script>