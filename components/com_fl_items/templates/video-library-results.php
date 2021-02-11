<div class="video-library-grid row fluid-grid">
	{loopstart}
		<div class="col-sm-4 fluid-grid-cell">
			<div class="video-library">
				{prop-video-value}
				<h6 class="text-center video-name">{item-name}</h6>
			</div>
		</div>
<!script type="application/ld+json">
    {
      "@context": "http://schema.org/",
      "@type": "VideoObject",
      "name": "{item-name}",
      {if-description}
      "description": "{prop-description-value}",
      {else}
      "description": "Travel tips and tricks from Accent on Travel - {item-name}",
      {endif}
      "@id": "{url}",
      "author": {
        "@type": "Person",
        "name": "Accent On Travel"
      }
    }
    </script>
	{loopend}
</div>