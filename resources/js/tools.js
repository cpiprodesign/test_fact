window.getContentMeta = function (name, return_dom_object = false) {
	return return_dom_object ? document.head.querySelector(`meta[name="${name}"]`) : document.head.querySelector(`meta[name="${name}"]`).content
}