<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Time</th>
      <th scope="col">Event</th>
      <th scope="col">Location</th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($response)) { ?>
      <?php foreach($response as $res){ ?>
          <?php if(!empty($res->ShipmentInfo->ShipmentEvent->ArrayOfShipmentEventItem)) { ?>
              <?php 
               $i = 1; 
              foreach($res->ShipmentInfo->ShipmentEvent->ArrayOfShipmentEventItem as $shipmentEvent){ ?>
                  <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo (string)$shipmentEvent->Date." ".(string)$shipmentEvent->Time; ?></td>
                    <td><?php echo (string)$shipmentEvent->ServiceEvent->Description; ?></td>
                    <td><?php echo (string)$shipmentEvent->ServiceArea->Description; ?></td>
                  </tr>         
              <?php $i++; } ?>  
          <?php } ?>  
      <?php } ?>  
    <?php } ?> 
  </tbody>
</table>