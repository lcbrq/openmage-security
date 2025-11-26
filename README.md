# LCB_Security

Simple security addon for OpenMage (Magento 1)

# Uninstall

```
DROP TABLE `lcb_security_request_post`;
DROP TABLE `lcb_security_request_rule`;
DELETE FROM `core_resource` WHERE `code` = "lcb_security_setup";
```