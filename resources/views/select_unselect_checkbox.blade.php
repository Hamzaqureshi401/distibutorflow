
    var arr = [];
    console.log('1w');
function checkboxselected(a){

   if(arr != ''){
       arr = arr +","+ a;
      }else{
        arr = a;
      }
      $('#select_box_val').val(arr);
}
function checkboxunselected(a){

   arr = (JSON.parse("[" + arr + "]")).filter(f => f !== a);
       //arr = JSON.parse("[" + arr + "]");
       $('#select_box_val').val(arr);
}
