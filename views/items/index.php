<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список позиций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', '#', ['class' => 'btn btn-success', 'id' => 'add-button']) ?>&nbsp;
        <?= Html::a('Редактировать', '#', [
                'class' => 'btn btn-warning',
                'id' => 'edit-button',
                'data-toggle' => 'modal',
                'data-target' => '#myModal',
        ]) ?>&nbsp;
        <?= Html::a('Удалить', '#', ['class' => 'btn btn-danger', 'id' => 'delete-button']) ?>
    </p>

    <table class="table table-bordered">
        <thead>
            <tr><th>Артикул</th><th>Наименование</th><th>Остаток</th><th>Ед. Изм.</th></tr>
        </thead>
        <tbody>
            <?php
            foreach($items as $item) {
                ?>
                    <tr class="clickable-row" data-id="<?=$item['id']?>">
                        <td class="td-article"><?=$item['article']?></td>
                        <td class="td-name"><?=$item['name']?></td>
                        <td class="td-remainder"><?=$item['remainder']?></td>
                        <td class="td-unit"><?=$item['unit']?></td>
                    </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <label>Редактировать данные</label>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-primary save">Сохранить</button>
                </div>
                <div class="modal-body">
                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#articleTab" aria-controls="articleTab" role="tab" data-toggle="tab">Артикул</a>

                            </li>
                            <li role="presentation"><a href="#nameTab" aria-controls="nameTab" role="tab" data-toggle="tab">Наименовани</a>

                            </li>
                            <li role="presentation"><a href="#remainderTab" aria-controls="remainderTab" role="tab" data-toggle="tab">Остаток на складе</a>

                            </li>
                            <li role="presentation"><a href="#unitTab" aria-controls="unitTab" role="tab" data-toggle="tab">Ед. Изм</a>

                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <input type="hidden" name="id">
                            <div role="tabpanel" class="tab-pane active" id="articleTab">
                                <div style="height: 20px"></div>
                                <label>Артикул</label> <input type="text" name="article">
                            </div>
                            <div role="tabpanel" class="tab-pane" id="nameTab">
                                <div style="height: 20px"></div>
                                <label>Наименование</label> <input type="text" name="name">
                            </div>
                            <div role="tabpanel" class="tab-pane" id="remainderTab">
                                <div style="height: 20px"></div>
                                <label>Остаток на складе</label> <input type="number" name="remainder">
                            </div>
                            <div role="tabpanel" class="tab-pane" id="unitTab">
                                <div style="height: 20px"></div> <input type="text" name="unit">
                                <label>Ед. Изм.</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
<?php
$js = <<< JS

function hasClass(elem, className) {
    return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}

function rowClickHandler(e)
{
    var tr = this;
    var isSelected = hasClass(tr, 'selected');
    document.querySelectorAll('tbody tr').forEach(function(value, key, parent){
        value.className = value.className.replace('selected', '');
    });
    if(!isSelected) tr.className = tr.className ? tr.className + ' selected' : 'selected';
}

document.querySelectorAll('.clickable-row').forEach(function(value, key, parent){
    value.onclick = rowClickHandler;
});

function createTd(className)
{
    var td = document.createElement('td');
     td.style.height = '30px';
     td.className = className;
     return td;
}
    
document.getElementById('add-button').onclick = function(e) {
    var tr = document.createElement('tr');
    tr.append(createTd('td-article'));
    tr.append(createTd('td-name'));
    tr.append(createTd('td-remainder'));
    tr.append(createTd('td-unit'));
    tr.onclick = rowClickHandler;
    tr.style.cursor = 'pointer';
    document.querySelector('tbody').append(tr);
    e.preventDefault();
};

document.getElementById('edit-button').onclick = function(e) {
    e.preventDefault();
};

document.getElementById('delete-button').onclick = function(e) {
    document.querySelector('tbody tr.selected').remove();
    e.preventDefault();
};

$("#myModal .save").on("click", function(e){
    e.preventDefault();
    var modal = $("#myModal");
    var id = modal.find('.modal-body input[name="id"]').val();
    var article = modal.find('.modal-body input[name="article"]').val();
    var name = modal.find('.modal-body input[name="name"]').val();
    var remainder = modal.find('.modal-body input[name="remainder"]').val();
    var unit = modal.find('.modal-body input[name="unit"]').val();
    
    if(id) {
        var url = '/items/' + id + '/update';
    } else {
        url = '/items/add';
    }
    fetch(url, {
        method: 'POST',
        body: JSON.stringify({
            article: article,
            name: name,
            remainder: remainder,
            unit: unit,
        }),
        headers: {
           'Content-Type': 'application/json;charset=utf-8'
        },
    }).then(function (value) { 
       return value.json(); 
     }).then(function (json){
         if(json['success']) {
             $('#myModal input').val('');
            $("#myModal").modal('hide');
            var selectedTr = document.querySelector('tbody tr.selected');
            selectedTr.setAttribute('data-id', json['data']['id']);
            selectedTr.querySelector('td.td-article').innerHTML = json['data']['article'];
            selectedTr.querySelector('td.td-name').innerHTML = json['data']['name'];
            selectedTr.querySelector('td.td-remainder').innerHTML = json['data']['remainder'];
            selectedTr.querySelector('td.td-unit').innerHTML = json['data']['unit'];
         } else {
            $("#myModal").find('.modal-footer').html(json['message'].join('<br>')); 
         }
     });
    
});

$('#myModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  
  var selectedTr = document.querySelector('tbody tr.selected')
  if(!selectedTr) {
      return;
  }
  var id = selectedTr.getAttribute('data-id');
  if(selectedTr.getAttribute('data-id')) {
      var article = selectedTr.querySelector('td.td-article').innerHTML
      var name = selectedTr.querySelector('td.td-name').innerHTML
      var remainder = selectedTr.querySelector('td.td-remainder').innerHTML
      var unit = selectedTr.querySelector('td.td-unit').innerHTML
      
      var modal = $(this)
      modal.find('.modal-body input[name="id"]').val(id);
      modal.find('.modal-body input[name="article"]').val(article);
      modal.find('.modal-body input[name="name"]').val(name);
      modal.find('.modal-body input[name="remainder"]').val(remainder);
      modal.find('.modal-body input[name="unit"]').val(unit);
  }
})
JS;
$this->registerJs($js);

