var HOME = BASE_URL + 'masters/warehouse/';

function goBack() {
  window.location.href = HOME;
}


function goAdd() {
  window.location.href = HOME + 'add_new';
}


function goEdit(id) {
  window.location.href = HOME + 'edit/'+id;
}



function saveAdd() {
    let name = $('#name').val();
    let status = 0;

    if($('#status').is(':checked')) {
        status = 1;
    }

    if(name.length == 0) {
        swal("ชื่อคลังไม่ถูกต้อง");
        return false;
    }

    load_in();
    $.ajax({
        url:HOME + 'add',
        type:'POST',
        cache:false,
        data:{
            'name' : name,
            'status' : status
        },
        success:function(rs) {
            load_out();
            if(rs === 'success') {
                swal({
                    title: 'Success',
                    type:'success',
                    timer:1000
                });

                setTimeout(function() {
                    window.location.reload();
                }, 1200);
            }
            else {
                swal({
                    title:'Error!',
                    text:rs,
                    type:'error'
                });
            }
        },
        error:function(xhr) {
            load_out();
            swal({
                title:'Error',
                text:xhr.responseText,
                type:'error',
                html:true
            });
        }
    });


}


function update() {
    let id = $('#warehouse_id').val();
    let name = $('#name').val();
    let old_name = $('#old_name').val();
    let status = 0;

    if($('#status').is(':checked')) {
        status = 1;
    }

    if(id == "" || id == 0) {
        swal("Error", "Invalid Warehouse Id", "error");
        return false;
    }

    if(name.length == 0) {
        swal("Error!", "กรุณาระบุชื่อคลัง", "error");
        return false;
    }

    load_in();
    $.ajax({
        url:HOME + 'update',
        type:'POST',
        cache:false,
        data:{
            'id' : id,
            'name' : name,
            'old_name' : old_name,
            'status' : status
        },
        success: function(rs) {
            load_out();
            if(rs === 'success') {
                swal({
                    title:'Success',
                    type:'success',
                    timer:1000
                });
            }
            else {
                swal({
                    title:'Error!',
                    text:rs,
                    type:'error'
                });
            }
        },
        error:function(xhr) {
            load_out();
            swal({
                title:'Error!',
                text:xhr.responseText,
                type:'error',
                html:true
            });
        }
    })
}



function getDelete(id, name){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ '+ name +' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    $.ajax({
      url: HOME + 'delete',
      type:'POST',
      cache:false,
      data:{
        'id' : id,
        'name' : name
      },
      success:function(rs){
        if(rs == 'success'){
          swal({
            title:'Success',
            type:'success',
            time: 1000
          });

          setTimeout(function(){
            window.location.reload();
          }, 1500)

        }
        else {
          swal({
            title:'Error!',
            text:rs,
            type:'error'
          });
        }
      }
    })
  })
}