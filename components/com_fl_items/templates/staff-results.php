
{type-description}
<div class="staff-bios-list">
	{loopstart}
		<div class="staff-bios">
			<div class="row">
				<div class="col-md-2">
				{ifimg}	<a href="{url}">{img-1}</a>{endifimg}
				</div>
				
				<div class="col-md-10">
					<h4><a href="{url}">{item-name}</a></h4>
					<div class="description">{prop-shortdescription-value}</div>
				</div>
			</div>
		</div>
	{loopend}
</div>