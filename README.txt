To use this extension:

1. Add static template "Pagewide CSS Classes" to your main TS template.
2. Add to your TS setup (if "page" is the name of your PAGE object): 
   page.bodyTagCObject =< plugin.tx_pageclasses_pi1

Now you are able to add CSS classes to your pages (page properties, TAB "Appearence"), 
which are added to the class attribute of the body tag. This gives you full flexibility
to add and remove CSS classes to special pages and branches of your pagetree. 

Additionaly css classes are added to your body tag, depending on the selection of your 
page layout (frontend & backend). 

Backend layout classes are useful, if you want to link backend layout changes to frontend 
layout changes easily.

Frontend layout classes are especially useful if you want authors to change frontend layout 
by changing frontend layout selection. Unfortunately there is no possibility to make use
of inheritance. 

Plugin works with TYPO3 4.5-8 LTS.