Magento SEO Extension
==================

This extension is the full version of http://www.magentogarden.com/magento-seo-extension.html, which is developed years ago before CE 1.7 released, but tested under CE 1.9 and EE 1.14. 

### Features 

- Image Sitemap Management

Add the following snippets into your sitemap, and you may find the standard under https://support.google.com/webmasters/answer/178636?hl=en


```XML
<image:image>
    <image:loc>
        http://www.your-domain.com/path/to/your/image.ext
    </image:loc>
    <image:title>Your Image Title</image:title>
    <image:caption>Your Image Caption</image:caption>
</image:image>
```

- Custom URL Management

Add any URLs into your sitemap, even the URL isn't a part of Magento

- Add "next" and "prev" tag into paginated pages

- Include HREFLANG tag in the head

- Friendly URL for RSS feed

- User Sitemap (to provide a user-friendly sitemap for customers, especially for search engine)

- Open Graph Support

- Content Analytics and Keyword Extraction using YQL

### How to use

- Copy all the files into your Magento installation would be an option.

- Using the scripts in the repository:

<pre>
python install.py /extension/repository /your/magento/installation/
</pre>

- Using Modegit (https://github.com/jreinke/modgit)

<pre>
modgit add seo-extension https://github.com/crossgate9/mage-seo-extension
</pre>
