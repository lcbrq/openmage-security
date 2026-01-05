# LCB_Security

Simple security addon for OpenMage (Magento 1)

# Changelog

1.2.0 - Add stopwords and rejected requests model
1.1.0 - Add turnstile and recaptcha check

# Uninstall

```
DROP TABLE `lcb_security_request_post`;
DROP TABLE `lcb_security_request_rule`;
DELETE FROM `core_resource` WHERE `code` = "lcb_security_setup";
```