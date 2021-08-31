var HOME = BASE_URL + 'masters/item/';

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
    let barcode = $.trim($('#barcode').val());
    let item_group_id = $('#item_group_id').val();
    let price = $('#price').val();
    let uom_id = $('#uom_id').val();
    let main_uom_id = $('#main_uom_id').val();
    let rate = $("#rate").val();
    let status = $("#status").val();
    let uom_item = [];
    var image	= $("#image")[0].files[0];



    if(name.length == 0) {
        $('#name').addClass('has-error');
        $('#name').focus();
        return false;
    }
    else {
      $('#name').removeClass('has-error');
    }


    if(item_group_id == "") {
      $('#item_group_id').addClass('has-error');
      return false;
    }
    else {
      $('#item_group_id').removeClass('has-error');
    }

    if(uom_id == "") {
      $('#uom_id').addClass('has-error');
      return false;
    }
    else {
      $('#uom_id').removeClass('has-error');
    }

    if(main_uom_id != "" && (rate == "" || rate == 0 || rate < 0)) {
      $('#rate').addClass('has-error');
      return false;
    }
    else {
      $('#rate').removeClass('has-error');
    }


    if($('.uom-item').length)
    {
      $('.uom-item').each(function(){
        let ds = {
          'id': $(this).val(),
          'rate' : $(this).data('rate')
        }

        uom_item.push(ds);
      })
    }

    var fd = new FormData();
  	fd.append('image', image);
  	fd.append('name', name);
  	fd.append('barcode', barcode);
  	fd.append('uom_id', uom_id);
  	fd.append('item_group_id', item_group_id);
  	fd.append('price', price);
  	fd.append('status', status);
  	fd.append('main_uom_id', main_uom_id);
  	fd.append('rate', rate);
    fd.append('uom_items', JSON.stringify(uom_item));


    load_in();

    $.ajax({
        url:HOME + 'add',
        type:'POST',
        cache:false,
        data:fd,
        processData:false,
        contentType:false,
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
    const item_id = $('#id').val();
    let name = $('#name').val();
    let barcode = $.trim($('#barcode').val());
    let item_group_id = $('#item_group_id').val();
    let price = $('#price').val();
    let uom_id = $('#uom_id').val();
    let main_uom_id = $('#main_uom_id').val();
    let rate = $("#rate").val();
    let status = $("#status").val();
    let image = $('#image')[0].files[0];
    let uom_item = [];



    if(name.length == 0) {
        $('#name').addClass('has-error');
        $('#name').focus();
        return false;
    }
    else {
      $('#name').removeClass('has-error');
    }


    if(item_group_id == "") {
      $('#item_group_id').addClass('has-error');
      return false;
    }
    else {
      $('#item_group_id').removeClass('has-error');
    }

    if(uom_id == "") {
      $('#uom_id').addClass('has-error');
      return false;
    }
    else {
      $('#uom_id').removeClass('has-error');
    }

    if(main_uom_id != "" && (rate == "" || rate == 0 || rate < 0)) {
      $('#rate').addClass('has-error');
      return false;
    }
    else {
      $('#rate').removeClass('has-error');
    }


    if($('.uom-item').length)
    {
      $('.uom-item').each(function(){
        let ds = {
          'id': $(this).val(),
          'rate' : $(this).data('rate')
        }

        uom_item.push(ds);
      })
    }

    // console.log(uom_item);
    // return false;

    var fd = new FormData();
    fd.append('id', item_id);
  	fd.append('image', image);
  	fd.append('name', name);
  	fd.append('barcode', barcode);
  	fd.append('uom_id', uom_id);
  	fd.append('item_group_id', item_group_id);
  	fd.append('price', price);
  	fd.append('status', status);
  	fd.append('main_uom_id', main_uom_id);
  	fd.append('rate', rate);
    fd.append('uom_items', JSON.stringify(uom_item));

    load_in();

    $.ajax({
        url:HOME + 'update',
        type:'POST',
        cache:false,
        processData:false,
        contentType:false,
        data:fd,
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


$('#u_rate').keyup(function(e) {
  if(e.keyCode === 13) {
    addUom();
  }
})


function changeLabel() {
  var uom_id = $('#uom_id').val();
  var sku = $('#uom_id option:selected').text();
  var main_uom_id = $('#main_uom_id').val();
  var main_uom = $('#main_uom_id option:selected').text();

  if(uom_id == "" && main_uom_id != "") {
    sku = "";
  }

  if(main_uom_id == "") {
    main_uom = "ตัวคูณ";
  }
  else {
    main_uom = "1 "+main_uom+" = ";
  }

  $('#sku_label').text(sku);
  $('#main_uom_label').text(main_uom);
  $('#u_sku_label').text(sku);
}



function newItemGroup() {
  $('#itemGroupName').val('');
  $('#itemGroupModal').modal('show');
}


function addNewItemGroup() {
  var name = $('#itemGroupName').val();
  if(name.length) {
    $.ajax({
      url:BASE_URL + 'masters/item_group/remote_add',
      type:'POST',
      cache:false,
      data:{
        'name' : name
      },
      success:function(rs) {
        $('#itemGroupModal').modal('hide');
        if(isJson(rs)) {
          var ds = $.parseJSON(rs);
          if(ds.result == 'success') {
            $('#item_group_id').html(ds.item);
          }
          else {
            swal({
              title:'Error!',
              type:'error',
              text:ds.message
            })
          }
        }
        else {

          swal({
            title:'Error',
            text:rs,
            type:'error',
            html:true
          })
        }
      },
      error:function(rs) {
        $('#itemGroupModal').modal('hide');
        swal({
          title:'Error!',
          text:rs.responseText,
          type:'error',
          html:true
        });
      }
    })
  }
}

$('#itemGroupModal').on('shown.bs.modal', function() {
  $('#itemGroupName').focus();
});


function newUom() {
  $('#uomName').val('');
  $('#uomModal').modal('show');
}

$('#uomModal').on('shown.bs.modal', function() {
  $('#uomName').focus();
});


function addNewUom() {
  var name = $('#uomName').val();
  var main_id = $('#main_uom_id').val();
  if(name.length) {
    $.ajax({
      url:BASE_URL + 'masters/uom/remote_add',
      type:'POST',
      cache:false,
      data:{
        'name' : name,
        'main_id' : main_id
      },
      success:function(rs) {
        $('#uomModal').modal('hide');
        if(isJson(rs)) {
          var ds = $.parseJSON(rs);
          if(ds.result == 'success') {
            $('#uom_id').html(ds.item);
            $('#main_uom_id').html(ds.main_item);
            $('#uom').html(ds.main_item);
            changeLabel();
          }
          else {
            swal({
              title:'Error!',
              type:'error',
              text:ds.message
            })
          }
        }
        else {

          swal({
            title:'Error',
            text:rs,
            type:'error',
            html:true
          })
        }
      },
      error:function(rs) {
        $('#itemGroupModal').modal('hide');
        swal({
          title:'Error!',
          text:rs.responseText,
          type:'error',
          html:true
        });
      }
    })
  }
}



function viewDetail(id) {
  $.ajax({
    url:HOME + 'get_detail/'+id,
    type:'GET',
    cache:false,
    success:function(rs) {
      if(isJson(rs)) {
        var ds = $.parseJSON(rs);
        var source = $('#detailTemplate').html();
        var data = ds;
        var output = $('#detailBody');

        render(source, data, output);

        $('#detailModal').modal('show');
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        })
      }
    }
  })
}

function addUom() {
  let no = $('#no').val();
  no = parseDefault(parseInt(no), 1);
  let id = $('#uom').val();
  let name = $('#uom option:selected').text();
  let rate = parseDefault(parseFloat($('#u_rate').val()), 0);

  if(id == "") {
    $('#uom').addClass('has-error');
    return false;
  }
  else {
    $('#uom').removeClass('has-error');
  }

  if(rate == 0) {
    $('#u_rate').addClass('has-error');
    $('#u_rate').focus();
    return false;
  }
  else {
    $('#u_rate').removeClass('has-error');
  }

  let source = $('#tag-template').html();
  let data = {
    "no" : no,
    "id" : id,
    "name" : name,
    "rate" : rate
  };

  let output = $('#uom-item-list');

  render_append(source, data, output);

  $('#uom').val('');
  $('#u_rate').val('');
  $('#u_uom_label').text('ตัวคูณ');
  $('#no').val(no+1);
  $('#form-group-uom').removeClass('hide');
}



function removeTag(id) {
  $('#tag-'+id).remove();
  $('#uom-item-'+id).remove();
  if($('.uom-item').length == 0) {
    $('#form-group-uom').addClass('hide');
  }
}


function changeULabel() {
  var uom_id = $('#uom_id').val();
  var sku = $('#uom_id option:selected').text();
  var uom = $('#uom').val();
  var u_label = $('#uom option:selected').text();

  if(uom_id == "" && uom == "") {
    sku = "";
    u_label = "";
  }
  else if(uom_id == "" && uom != "") {
    sku = "";
  }
  else {
    u_label = "1 "+u_label+" = ";
  }


  $('#u_sku_label').text(sku);
  $('#u_uom_label').text(u_label);
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


function readURL(input)
{
   if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#previewImg').html('<img id="previewImg" src="'+e.target.result+'" width="200px" alt="รูปสลิปของคุณ" />');
        }
        reader.readAsDataURL(input.files[0]);
    }
}






$("#image").change(function(){
  if($(this).val() != '')
  {
    var file 		= this.files[0];
    var name		= file.name;
    var type 		= file.type;
    var size		= file.size;
    if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
    {
      swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
      $(this).val('');
      return false;
    }
    if( size > 2000000 )
    {
      swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error");
      $(this).val('');
      return false;
    }
    readURL(this);
    $("#btn-select-file").css("display", "none");
    $("#block-image").animate({opacity:1}, 1000);
  }
});


function changeImage() {
  $('#image').click();
}
