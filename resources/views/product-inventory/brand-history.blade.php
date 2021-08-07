<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th width="1%">No</th>
                <th width="1%">Brand ID</th>
                <th width="5%">Brand</th>
                <th width="15%">Total</th>
                <?php 
                $date = date("Y-m-d");
                $listdates = [];
                for ($i=0; $i < 3; $i++) { 
                    $listdates[] = $date;
                    echo '<th>'.$date.'</th>';
                    $date = date("Y-m-d",strtotime($date." -1 day"));
                } ?>
            </tr>
            <tbody class="">
                <?php foreach($inventory as $c => $i) { ?>
                    <tr style="<?php echo ($i->total <= 10 ) ? 'background: red' : ''; ?>">
                        <td><?php echo $c+1 ?></td>  
                        <td><?php echo $i->brand ?></td>  
                        <td><?php echo $i->name ?></td>  
                        <td><?php echo $i->total ?></td>  
                        <?php foreach($listdates as $d) {  ?>
                               <td><?php echo $i->totalBrandsLink($d,$i->brand); ?></td>   
                        <?php } ?> 
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>