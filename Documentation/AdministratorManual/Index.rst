.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================



Installation
------------

Install as usual. See next section for TypoScript configuration.



.. _admin-config:

Configuration
-------------

To enable the extension you have to add this TypoScript setup:

::

     config.simulateStaticUrls = 1


Everything else has default values and doesn't necessarily need special configuration.


Here's a full example of all config options:

::

     config {
        simulateStaticUrls = 1
        simulateStaticUrls {

            language = 1
            language {
                0 = de
                1 = en/gb
            }

            path = 1
            pathSegmentMaxLength =
            pathSegmentSmartTruncate =
            pathSegmentReplacementChar = _
            pathFormat = lowercase

            titleMaxLength = 40
            titleSmartTruncate =
            titleReplacementChar = _
            titleFormat = lowercase

            // if you want some parameters shorter
            parameterEncodingType = md5
            // list of parameters to be encoded others will be excluded
            parameterEncodingInclude = cHash, print, tx_ttnews[backPid], tx_ttnews[tt_news], tx_ttnews[pS], tx_ttnews[pL], tx_ttnews[arc], tx_ttnews[cat], tx_ttnews[pointer], tx_ttnews[swords]
            // list of parameters to be excluded from encoding
            parameterEncodingExclude =

        }

        // disable the other ones
        tx_realurl_enable = 0
        simulateStaticUrls = 0
     }


The configuration is similar to the older simulatestatic extension. Except of the PATH_INFO mode the extension can be
configured to work mostly like simulatestatic.

Properties
^^^^^^^^^^

.. container:: ts-properties

   ===================================================== ===================================
   Property                                              Data type
   ===================================================== ===================================
   `simulateStaticUrls`_                                 boolean
   `simulateStaticUrls.language`_                        int
   `simulateStaticUrls.language0`_                       string
   `simulateStaticUrls.titleMaxLength`_                  int
   `simulateStaticUrls.titleSmartTruncate`_              boolean
   `simulateStaticUrls.titleReplacementChar`_            string
   `simulateStaticUrls.titleFormat`_                     string
   `simulateStaticUrls.path`_                            boolean
   `simulateStaticUrls.pathSegmentMaxLength`_            int
   `simulateStaticUrls.pathSegmentSmartTruncate`_        boolean
   `simulateStaticUrls.pathSegmentReplacementChar`_      string
   `simulateStaticUrls.pathSegmentFormat`_               string
   `simulateStaticUrls.parameterEncodingType`_           string
   `simulateStaticUrls.parameterEncodingInclude`_        string
   ===================================================== ===================================




.. ### BEGIN~OF~TABLE ###


.. _setup-config-simulatestaticurls:

simulateStaticUrls
""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls

   Data type
         boolean

   Description
         If set TYPO3 makes all links in another way than usual. With  Apache mod\_rewrite has to be enabled and configured in
         httpd.conf for use of this in the ".htaccess"-files.

         Include this in the .htaccess file

         ::

            RewriteEngine On
            RewriteRule   ^[^/]*\.html$  index.php

         This means that any "\*.html"-documents should be handled by
         index.php.

         TYPO3 provides already a more sophisticated rewrite configuration in an example .htacces file. That should be used.

         Now if is done, TYPO3 will interpret the url of the html-document like
         this:

         [title].[id].[type].[language].html


         **Example:**

         TYPO3 will interpret this as page with uid=23 and type=1 :

         ::

            Startpage.23.0.0.html


   Default
         false



.. _setup-config-simulatestaticurls-language:

simulateStaticUrls.language
"""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.language

   Data type
         boolean

   Description
         If set, a language iso code will be added to the url as path segment if the language uid > 0 and in the language record the iso code is set.

        If you want the same for the default language (0) or different codes for the languages, there's additional configuration needed with language0, language1, and so on.

         **Example:**

         ::

            /en/Startpage.23.1.0.html

         instead of the default, "Startpage.23.1.0.html", without the /en/.

   Default
        true


.. _setup-config-simulatestaticurls-language0:

simulateStaticUrls.language0
""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.language0
         simulateStaticUrls.language1
         ...

   Data type
         string

   Description
          If you want the default language (0) as path segment or different codes for the languages > 0,
          there's additional configuration needed with language0, language1, and so on.

         **Example:**

         ::

             simulateStaticUrls.language0 = de
             simulateStaticUrls.language1 = en/gb


         ::

            /de/Startseite.23.0.0.html
            /en/gb/Startpage.23.0.1.html

         This can be handy if a system language is not only a language but also used as a content dimension like a country.

   Default
         undefined


.. _setup-config-simulatestaticdocuments-titlemaxlength:

simulateStaticUrls.titleMaxLength
"""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.titleMaxLength

   Data type
         int

   Description
         If set, TYPO3 generates urls with the title in, limited to the
         first [simulateStaticUrls.titleMaxLength] number of chars.

         **Example:**

         ::

            very_cool.23.0.1.html

         instead of the full title, "very_cool_stuff_here.23.0.1.html", with titleMaxLength=9.

   Default
         40



.. _setup-config-simulatestaticurls-titlesmarttruncate:

simulateStaticUrls.titleSmartTruncate
"""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.titleSmartTruncate

   Data type
         boolean

   Description
         If set, title will be truncated at word boundaries if possible when also titleMaxLength is set.

         **Example:**

         ::

            very_cool.23.0.1.html

         instead of the full title, "very_cool_st.23.0.1.html", with titleMaxLength=12.

   Default
         false


.. _setup-config-simulatestaticurls-titlereplacementchar:

simulateStaticUrls.titleReplacementChar
"""""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.titleReplacementChar

   Data type
         string

   Description
         Word separator for URLs generated by simulateStaticUrls.

         Typical values are underscore "\_" or hyphen "-".

         **Example:**

         ::

            very_cool_stuff_here.23.0.1.html
            very-cool-stuff-here.23.0.1.html

   Default
        \_ (underscore)


.. _setup-config-simulatestaticurls-titleformat:

simulateStaticUrls.titleFormat
""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.titleFormat

   Data type
         lowercase, uppercase, camelcase

   Description
         Defines the case of the title text.

         **Example:**

         ::

            very_cool_stuff_here.23.0.1.html
            VERY_COOL_STUFF_HERE.23.0.1.html
            very_Cool_Stuff_Here.23.0.1.html

   Default
        lowercase


.. _setup-config-simulatestaticdocuments-path:

simulateStaticUrls.path
"""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.path

   Data type
         boolean

   Description
         If set, TYPO3 generates urls with a path (and not only a filename).

        Keep in mind the path itself has no functionality and is ignore by the system when decoding an url. When the user shortens the url to access a different 'directory' that will not work. But that also doesn't work very well with other xxxurl extension.

         **Example:**

         ::

            /this/is/a_path/which_looks_like_a_real_path_but_is_fake/very_cool.23.0.1.html


   Default
        true

.. _setup-config-simulatestaticdocuments-pathsegmentmaxlength:

simulateStaticUrls.pathSegmentMaxLength
"""""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.pathSegmentMaxLength

   Data type
         int

   Description
         If set, the generated path segments are limited to the
         first [simulateStaticUrls.pathSegmentMaxLength] number of chars.

         **Example:**

         ::

            /this/is/a_path/which_looks/very_cool.23.0.1.html

         instead of the full path segment, "../which_looks_like_a_real_path_but_is_fake/..", with pathSegmentMaxLength=12.

   Default
        0 (false)

.. _setup-config-simulatestaticurls-pathsegmentsmarttruncate:

simulateStaticUrls.pathSegmentSmartTruncate
"""""""""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.pathSegmentSmartTruncate

   Data type
         boolean

   Description
         If set, path segment will be truncated at word boundaries if possible when also pathSegmentMaxLength is set.

         **Example:**

         ::

            /this/is/a_path/which_looks/very_cool.23.0.1.html

         instead of the full path segment, "../which_looks_like_a_real_path_but_is_fake/..", with pathSegmentMaxLength=15.

   Default
         false


.. _setup-config-simulatestaticurls-pathsegmentreplacementchar:

simulateStaticUrls.pathSegmentReplacementChar
"""""""""""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.pathSegmentReplacementChar

   Data type
         string

   Description
         Word separator for URLs generated by simulateStaticUrls.

         Typical values are underscore "\_" or hyphen "-".

         **Example:**

         ::

            /this/is/a_path/which_looks/cool.23.0.1.html
            /this/is/a-path/which-looks/cool.23.0.1.html

   Default
        \_ (underscore)


.. _setup-config-simulatestaticurls-pathsegmentformat:

simulateStaticUrls.pathSegmentFormat
""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.pathSegmentFormat

   Data type
         lowercase, uppercase, camelcase

   Description
         Defines the case of the pathSegment text.

         **Example:**

         ::

            /this/is/a_path/which_looks/cool.23.0.1.html
            /THIS/IS/A_PATH/WHICH_LOOKS/cool.23.0.1.html
            /This/Is/A_Path/Which_Looks/cool.23.0.1.html

   Default
        lowercase


.. _setup-config-simulatestaticurls-parameterencodingtype:

simulateStaticUrls.parameterEncodingType
""""""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.parameterEncodingType

   Data type
         string

   Description
         Allows you to also encode additional parameters into the simulated
         filename.

         **Example:**

         You have a news-plugin. The main page has the url "News.228.0.0.html"
         but when one clicks on a news item the url will be
         "News.228.0.0.html?tx\_mininews\_pi1[showUid]=2&cHash=b8d239c224"
         instead.

         Now, this URL might not look like you expect. These are the options:

         **Value set to "base64":**

         This will transform the filename used to this value: "News.228+B6Jn
         R4X21pbmluZXdzX3BpMVtzaG93VWlkXT0yJmNIYXNoPWI4ZDIzOWMyMjQ\_.0.0.html".
         The query string has simply been base64-encoded (and some more...) and
         added to the HTML-filename. The really great thing about this that the filename is self-
         reliant because the filename contains the parameters. The downside to
         it is the very very long filename.

         **Value set to "md5":**

         This will transform the filename used to this value:

         "News.228+M57867201f4a.0.0.html". Now, what a lovely, short filename!
         Now all the parameters has been hashed into a 10-char string inserted
         into the filename. At the same time an entry has been added to the cache_md5params
         table in the database so when a request for this filename reaches the
         frontend, then the REAL parameter string is found in the database! The
         really great thing about this is that the filename is very short
         (opposite to the base64-method). The downside to this is that IF you
         clear the database cache table at any time, the URL here does NOT work
         until a page with the link has been generated again (re-inserting the
         parameter list into the database).

         **NOTICE:** The encoding will work only on parameters
         that are manually entered in the list set by
         .simulateStaticUrls.parameterEncodingInclude (see right below) or those
         parameters that various plugins might allow in addition. This is to
         limit the run-away risk when many parameters gets combined.

   Default
        md5


.. _setup-config-simulatestaticurls-parameterencodinginclude:

simulateStaticUrls.parameterEncodingInclude
"""""""""""""""""""""""""""""""""""""""""""

.. container:: table-row

   Property
         simulateStaticUrls.parameterEncodingInclude

   Data type
         string

   Description
         A list of url parameter that may be a part of the md5/base64 encoded part
         of a virtual filename (see property in the
         row above).

         **Example:**

         ::

            simulateStaticUrls.parameterEncodingInclude = cHash, tx_news_pi1[news], tx_news_pi1[controller], tx_news_pi1[action]


   Default
        none



.. ###### END~OF~TABLE ######






Maintenance
-----------

With the TypoScript configuration :ref:`setup-config-simulatestaticurls-parameterencodingtype` to *md5* url parameters
will be stored in the database table cache_md5params. If the data of that table get lost all parameters for the urls are
lost too, but the pages will still be delivered with the right type. The field tstamp of the table stores a timestamp of
the last access. So you could deleted entries with old timestamps if you like.
