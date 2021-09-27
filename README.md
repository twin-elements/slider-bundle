#Installation
1.composer 

2. in bundles.php add 
3. ```TwinElements\SliderBundle\TwinElementsSliderBundle::class => ['all' => true],```

3.Add to routes.yaml
```
slider_admin:
    resource: "@TwinElementsSliderBundle/Controller/"
    prefix: /admin
    type: annotation
    requirements:
        _locale: '%app_locales%'
    defaults:
        _locale: '%locale%'
        _admin_locale: '%admin_locale%'
    options: { i18n: false }
```

4. in twin_elements_slider.yaml add 
```
twin_elements_slider:
   image_size: { width: 1920, height: 600 }
   mobile_image_size: { width: 600, height: 600 }
```
