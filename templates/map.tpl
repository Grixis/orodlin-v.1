{strip}
<map name="map">
<!-- #$-:Image map file created by GIMP Image Map plug-in -->
<!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
<!-- #$-:Please do not edit lines starting with "#$" -->
<!-- #$VERSION:2.3 -->
<!-- #$AUTHOR:Marek Stasiak -->
<area shape="poly" coords="135,598,194,529,324,520,373,551,342,609,234,620,160,618,141,600" alt="{$Ameredith}" title="{$Ameredith}"  href="travel.php?action=powrot" />
<area shape="poly" coords="11,704,19,674,94,662,130,629,137,611,190,638,198,639,166,713,141,748,115,802,96,857,88,912,10,949,9,842,11,765,11,705" alt="{$Aforest}" title="{$Aforest}" href="travel.php?action=las" />
<area shape="poly" coords="828,730,858,700,916,708,980,706,979,741,906,741,861,736,858,736" alt="{$Aheimeroth}" title="{$Aheimeroth}" href="#" />
<area shape="poly" coords="793,877,825,847,850,865,949,862,958,885,931,891,853,891,826,887" alt="{$Aridge}" title="{$Aridge}" href="#" />
<area shape="poly" coords="796,792,836,750,888,755,945,760,966,788,967,825,903,840,826,836,812,822" alt="{$Aagarakar}" title="{$Aagarakar}" href="travel.php?action=city2" />
<area shape="poly" coords="703,873,725,810,737,768,747,710,741,694,799,644,829,672,863,683,920,701,850,696,823,730,832,738,982,746,982,760,813,741,791,790,815,839,919,847,969,830,970,784,958,765,982,768,981,859,917,858,852,860,827,844,783,877,840,899,960,891,958,867,979,865,986,967,970,987,940,941,890,928,826,936,732,913,724,898" alt="{$Amountains}" title="{$Amountains}" href="travel.php?action=gory" />
</map>
<map name="map_small">
<!-- #$-:Image map file created by GIMP Image Map plug-in -->
<!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
<!-- #$-:Please do not edit lines starting with "#$" -->
<!-- #$VERSION:2.3 -->
<!-- #$AUTHOR:Marek Stasiak -->
<area shape="poly" coords="68,327,114,291,154,282,191,289,215,331,157,339,83,334" alt="{$Ameredith}" title="{$Ameredith}" href="travel.php?action=powrot" />
<area shape="poly" coords="6,398,7,370,45,364,70,348,75,335,112,338,107,369,90,395,77,410,69,434,59,462,48,501,5,525,6,484,5,440,6,402" alt="{$Aforest}" title="{$Aforest}" href="travel.php?action=las" />
<area shape="poly" coords="439,439,453,410,482,414,528,429,534,447,518,456,469,456,449,457,443,446" alt="{$Aagarakar}" title="{$Aagarakar}" href="travel.php?action=city2" />
<area shape="poly" coords="457,397,472,383,495,387,537,389,538,406,514,410,474,405" alt="{$Aheimeroth}" title="{$Aheimeroth}" href="#" />
<area shape="poly" coords="438,481,456,465,486,469,527,473,525,491,476,493,447,487" alt="{$Aridge}" title="{$Aridge}" href="#" />
<area shape="poly" coords="384,478,404,423,409,402,405,383,438,355,458,371,489,376,501,385,467,380,453,400,467,406,499,410,538,413,535,428,484,413,453,407,436,439,447,460,535,457,540,472,454,463,434,482,472,496,533,492,538,492,527,491,529,475,538,474,541,511,538,543,522,531,515,515,473,513,425,509,390,492" alt="{$Amountains}" title="{$Amountains}" href="travel.php?action=gory" />
</map>
{literal}
<script type="text/javascript">
function changemap() {
	src = document.getElementById("mapa").src;
	umap = document.getElementById("mapa").useMap;
	if (src.match("small") == null) {
		src = "images/map/map_small.jpg";
		umap = "#map_small";
		}
	else {
		src = "images/map/map.jpg";
		umap = "#map";
		}
	document.getElementById("mapa").src = src;
	document.getElementById("mapa").useMap = umap;
	}
</script>
{/literal}
<div class="center" style="width=560px;height:560px;overflow:auto"><img id="mapa" src="" useMap="#map_small" border="0" onclick="changemap()" alt="img_map" /></div>

{/strip}
