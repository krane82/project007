<?php

print '<form method="POST" action="'. __HOST__ .'/docs/terms/download.php">';

print '<table class="table table-hover">

			<thead>

				<tr>

					<th>Terms and Conditions copy</th>

					<th>Docusign Information</th>

				</tr>

			</thead>

			<tbody>

				<tr>';

foreach ($item as $it)

{

    if ($it=='.' || $it=='..')continue;

    print '<td><button name="file" class="btn btn-sm btn-link" value="'.$it.'" target="a_blank" title="Download Current">'.$it.'</button></td>';

}

print '</tr></tbody></table>';

print '</form>';



