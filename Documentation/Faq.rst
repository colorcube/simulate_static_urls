.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: Includes.txt


.. _faq:

FAQ
----------

Cool! Does it replace realurl?
""""""""""""""""""""""""""""""

No. ``simulate_static_urls`` offers only limited functionality and is no
match to existing :ref:`alternatives` in terms of features and flexibility.

Okay, then what does it do?
"""""""""""""""""""""""""""

It just creates a speaking path for each page based on its rootline. So
the rootline "Service > About us" results in the path
``/service/about-us.3.0.0.html``.

-  It respects the ``nav_title`` field of ``pages``.
-  It respects the ``config.absRefPrefix`` TypoScript setting.
-  Shortcut pages are handled correctly (their path is the one from
   their target page, just like in realurl)

What about extension parameters?
""""""""""""""""""""""""""""""""

Parameters can be shortened but can't be converted to url path segments.

What about multi language handling?
"""""""""""""""""""""""""""""""""""

Yes! No problem.

What about mountpoints, workspaces, ...?
""""""""""""""""""""""""""""""""""""""""

Not supported yet. Those might follow as there is demand for it.

Is 'page not found' handling included?
""""""""""""""""""""""""""""""""""""""

No. When it makes sense it might be included later, but for now go with an extension which provides that functionality.
You might try the auto404 extension.

Can I change the configuration without breaking urls?
"""""""""""""""""""""""""""""""""""""""""""""""""""""

Yes. The url itself has the information to decode it. You can change the format and still the old urls can be decoded.

But that doesn't fit my requirements!
"""""""""""""""""""""""""""""""""""""

Okay, just go with :ref:`alternatives` then :)
