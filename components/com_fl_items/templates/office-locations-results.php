<div class="office-locations-grid row fluid-grid">
	{loopstart}
		<div class="col-sm-4 fluid-grid-cell">
			<div class="office-locations">
				<h6>{item-name}</h6>
				<p>{prop-street-value}{if-street2}, {prop-street2-value}{endif}<br>{prop-city-value},{prop-state-value} {prop-zip-value}</p>
			</div>
		</div>
		<!script type="application/ld+json">
    { "@context" : "http://schema.org",
      "@type" : "Organization",
      "url" : "http://www.accentontravel.com",
      "contactPoint" : [

        { "@type" : "ContactPoint",
          "telephone" : "+1-{prop-phone-value}",
          "contactType" : "customer service",
          "contactOption" : "Accent On Travel {item-name}",
          "areaServed" : "{prop-city-value}, {prop-state-value}"
        }
         ] }
</script>
	{loopend}
</div>