-- brandsDealers

ALTER TABLE `brandsDealers`
ADD FOREIGN KEY (`brandId`) REFERENCES `brands` (`id`);

ALTER TABLE `brandsDealers`
ADD FOREIGN KEY (`dealerId`) REFERENCES `dealers` (`id`);

-- brandsHeadings

ALTER TABLE `brandsHeadings`
ADD FOREIGN KEY (`brandId`) REFERENCES `brands` (`id`);

ALTER TABLE `brandsHeadings`
ADD FOREIGN KEY (`headingId`) REFERENCES `headings` (`id`);

-- dealersHeadings

ALTER TABLE `dealersHeadings`
ADD FOREIGN KEY (`dealerId`) REFERENCES `dealers` (`id`);

ALTER TABLE `dealersHeadings`
ADD FOREIGN KEY (`headingId`) REFERENCES `headings` (`id`);

-- rolesPermissions

ALTER TABLE `rolesPermissions`
ADD FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`);

ALTER TABLE `rolesPermissions`
ADD FOREIGN KEY (`permissionId`) REFERENCES `permissions` (`id`);
