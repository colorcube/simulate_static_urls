# simulate_static_urls

This is a TYPO3 extension.

## What does it do?

Adds the possibility to have speaking urls in the TYPO3 frontend pages. This means instead of having urls (links) like this

    /index.php?id=42&L=1

the extension creates urls like this

    /en/topics/interesting/cool_new_stuff_here.42.0.1.html

For more information have a look in documentation.


### ASCII urls

A special feature is that ascii text will be generated from languages with non-ascii characters.

For example the page title

    зимой холодно

will be converted to the text

    zimoi_kholodno


## How does it work

Other speaking url solutions have a problem. It is impossible to decode their urls to a TYPO3 page without storing that url before with the relation to the page. If you loose that information you can't decode the url. This is broken by design.

(To be honest simulatestatic has also a feature to encode parameters in shorter urls which produces problems if you deleted the hash table. But still the right page would be displayed.)

If you prefer an url like */en/topics/interesting/cool_new_stuff_here/* you might want to have a look at other xxxurl extensions. But maybe you want to hear what the meaning of the three numbers is:

    /en/topics/interesting/cool_new_stuff_here.42.0.1.html

Except the original simulatestatic extension, all other xxxurl extensions have the problem that the url can not be decoded until the page is rendered once. Normally that's not a problem until your url registry got corrupt. 
The second thing is that moving pages to a new location has to be tracked. Otherwise the page would no longer be found with the first url. There are also problems with duplicate names of pages and path ...

The urls created by this extension include

- the page id
- the page type
- the language

With that information a page can be found and delivered no matter what the rest of the url looks like. You now what? In fact the rest of the url is completely ignored and has no meaning to the system.

Unfortunately ... there are parameters. There's no magic to remove them, so there's an registry for parameters when you choose to shorten the url with parameters. 
But if that's lost the url is still working and shows the right page, but maybe not the single news entry what would normally displayed.



## Contribute


*Git Repository*

https://github.com/colorcube/simulate_static_urls

*Issue Tracker*

https://github.com/colorcube/simulate_static_urls/issues


- Send pull requests to the repository
- Use the issue tracker for feedback and discussions


## TODO

- simulateStaticUrls.parameterEncodingInclude has to be set to make md5 work?
- remove simulateStaticUrls.parameterEncodingExclude?