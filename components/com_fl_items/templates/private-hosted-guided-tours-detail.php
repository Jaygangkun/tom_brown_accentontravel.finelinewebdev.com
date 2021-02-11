<div class="private-hosted-guided-tours">
	<div class="row">
	    <div class="col-12"><h1>{item-name}</h1>
				<h2 class="headline">{prop-headline-value}</h2>
				<p>{prop-length-value} departing {prop-departing-value}</p>
				{img-gridlightbox-3}
		</div>
		<div class="col-md-6">
		    {if-details}
			<div class="details"><h3>Travel Dates</h3>{prop-details-value}</div>
			{endif}
			{if-highlights}
				<div class="highlights px-3"><h3>Vacation Highlights</h3>{prop-highlights-value}</div>
		    {endif}
		</div>
		<div class="col-md-6">
			<div class="private-hosted-guided-tours-details">
			{if-ratesinclude}
				<div class="ratesinclude"><h3>Per Person Rates Include</h3>{prop-ratesinclude-value}</div>
			{endif}	
			{if-download}
				<div class="download"><a href="{prop-download-value}" class="btn btn-primary btn-lg" target="_blank">Download Brochure</a></div>
			{endif}
			</div>
		</div>
		{if-contactmessage}
		<div class="col-12 contactmessage">{prop-contactmessage-value}</div>
		{endif}
	</div>
</div>