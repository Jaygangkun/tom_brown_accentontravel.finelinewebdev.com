<div class="news-list-grid row fluid-grid">
	{loopstart}
		<div class="col-sm-4 fluid-grid-cell">
			<div class="news-article">
			    <a href="{prop-link-value}" target="_blank">
			    {img-1}
			    <div class="col-sm-12">
				<h6>{item-name}</h6>
				<div class="small date">{prop-publisher-value} <span class="float-right">{prop-date-value}</span></div>
				<div class="description">{prop-shortdescription-value}</div>
				</div>
				</a>
			</div>
		</div>
<!script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{url}"
  },
  "headline": "{item-name}",
  {ifimg}
  "image": "{img-1-path}",
  {endifimg}
  "datePublished": "{prop-date-value}",
  "dateModified": "{prop-date-value}",
  "author": {
    "@type": "Person",
    "name": "{if-author}{prop-author-value}{else}Annette Stellhorn{endif}"
  },
   "publisher": {
    "@type": "Organization",
    "name": "{prop-publisher-value}",
    "logo": {
      "@type": "ImageObject",
      "url": "/images/layout/{prop-publisher-value}_logo.png"
    }
  },
  "description": "{prop-shortdescription-value}"
}
</script>
	{loopend}
</div>