
<table class="table table-bordered">
    <tr>
        <td>

            <table class="table table-bordered">
                <tr>
                    <td><?php echo count($old_model); ?></td>
                    <td>old PLAN id1</td>
                    <td>old CREDIT id</td>
                </tr>
                <?php foreach($old_model as $old): ?>
                    <tr>
                        <td></td>
                        <td><?php echo $old->id; ?></td>
                        <td><?php echo $old->credit_id; ?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        </td>
        <td> ---- </td>
        <td>

            <table class="table table-bordered">
                <tr>
                    <td><?php echo count($new_model); ?></td>
                    <td>new PLAN id1</td>
                    <td>new CREDIT id</td>
                </tr>
                <?php foreach($new_model as $new): ?>
                    <tr>
                        <td></td>
                        <td><?php echo $new->id; ?></td>
                        <td><?php echo $new->credit_id; ?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        </td>
    </tr>
</table>
