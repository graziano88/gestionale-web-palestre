
<style>
	h1 { color:green; }
	div {margin: 20px; }
</style>

	<h1>Titolo del corpo</h1>
	<div>
		<table>
		
<?php
for($i=0; $i<100; $i++)	{
?>
			<tr>
				<td>Valore <?=$i+1?>:1</td>
				<td>Valore <?=$i+1?>:2</td>
				<td>Valore <?=$i+1?>:3</td>
				<td>Valore <?=$i+1?>:4</td>
			</tr>
<?php
}
?>
		</table>
	</div>
