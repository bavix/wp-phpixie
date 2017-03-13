
CREATE TABLE `dealersAddresses` (
  `id` int(11) NOT NULL,
  `dealerId` int(11) NOT NULL,
  `addressId` int(11) NOT NULL
) ENGINE=InnoDB;

ALTER TABLE `dealersAddresses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dealersAddresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;