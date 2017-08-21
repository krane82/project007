<?php
print '<form method="POST" action="'. __HOST__ .'/docs/invoices/download.php">';
print '<ul class="list-inline">';
foreach ($item as $it)
{
    if ($it=='.' || $it=='..')continue;
    print '<li><button name="file" class="btn btn-sm btn-link" value="'.$it.'" title="Download Current">'.$it.'</button></li>';

}
print '</form>';
print '</ul>';
