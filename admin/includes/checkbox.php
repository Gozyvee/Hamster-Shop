<?php $product_id = 1; ?>
<thead>
<th><input id="selectAllBoxes" type="checkbox"></th>
</thead>
<tbody>
    <td><input class='CheckBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $product_id ?>'></td>
    <td>John</td>
    <td>Joe</td>
</tbody>
<script>
     $(document).ready(function(){
    $('#selectAllBoxes').click(function(event){
        if(this.checked) {
            $('.CheckBoxes').each(function(){
                this.checked = true;
            });
        } else {
            $('.CheckBoxes').each(function(){
                this.checked = false;
            });
        }
    });
  });
</script>

