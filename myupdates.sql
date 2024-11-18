CREATE TABLE `violations` (
  `violation_id` int(11) NOT NULL,
  `violation_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `points_deducted` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`violation_id`, `violation_name`, `description`, `points_deducted`, `price`, `category_id`) VALUES
(1, 'Over 30 km/h limit', 'Exceeding speed limit by 30 km/h or more', 5, 150.00, 1),
(2, 'Between 10-30 km/h limit', 'Exceeding speed limit by 10-30 km/h', 3, 100.00, 1),
(3, 'Below 10 km/h limit', 'Exceeding speed limit by less than 10 km/h', 1, 50.00, 1),
(4, 'Illegal Parking', 'Parking in a restricted area without authorization', 2, 75.00, 2),
(5, 'Obstruction', 'Blocking a driveway or emergency access', 3, 90.00, 3),
(6, 'Driving under the influence', 'Operating a vehicle under the influence of alcohol or drugs', 10, 500.00, 5),
(7, 'Dangerous Driving', 'Driving in a way that endangers lives', 8, 300.00, 3),
(8, 'Not Wearing Seatbelt', 'Failing to wear a seatbelt while driving', 2, 75.00, 6),
(9, 'Using Cell Phone', 'Using a handheld cell phone while driving', 3, 125.00, 5),
(10, 'Running Red Light', 'Failing to stop at a red light', 6, 250.00, 4),
(11, 'Exceeding 40 km/h limit', 'Exceeding speed limit by more than 40 km/h', 7, 180.00, 1),
(12, 'No Parking Zone', 'Parking in a zone marked as \"No Parking\"', 2, 60.00, 2),
(13, 'Double Parking', 'Parking next to a parked car in a traffic lane', 3, 85.00, 2),
(14, 'In School Zone', 'Speeding in a designated school zone', 4, 200.00, 5),
(16, 'DUI Over Limit', 'Driving under the influence with BAC over legal limit', 10, 600.00, 6),
(21, 'Driving without license', 'Can\'t drive without the driving license', 5, 500.00, 8),
(22, 'Driving without license', 'Can\'t drive without the driving license', 5, 500.00, 8),
(23, 'Driving without license', 'Can\'t drive without the driving license', 5, 500.00, 8),
(24, 'Driving without license', 'Can\'t drive without the driving license', 5, 500.00, 8),
(26, 'a violation edited', 'violation description edited 2', 12, 1000.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `violation_categories`
--

CREATE TABLE `violation_categories` (
  `category_Id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violation_categories`
--

INSERT INTO `violation_categories` (`category_Id`, `category_name`) VALUES
(1, 'Speeding'),
(2, 'Parking Violation'),
(3, 'Signal Violation'),
(4, 'Driving Conduct Violations'),
(5, 'Safety Violations'),
(6, 'Court violations'),
(7, 'Recless driving'),
(8, 'Documents Violation'),
(9, 'Behaviour'),
(10, 'Other Violations');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_id`),
  ADD KEY `fk` (`driver_id`),
  ADD KEY `fk2` (`officer_id`),
  ADD KEY `fk3` (`violation_id`),
  ADD KEY `driver_id` (`driver_id`,`officer_id`,`violation_id`),
  ADD KEY `driver_id_2` (`driver_id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `police_station` (`police_station`);

--
-- Indexes for table `police_stations`
--
ALTER TABLE `police_stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `police_stations_draft`
--
ALTER TABLE `police_stations_draft`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_driver_profile_requests`
--
ALTER TABLE `update_driver_profile_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`violation_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `violation_categories`
--
ALTER TABLE `violation_categories`
  ADD PRIMARY KEY (`category_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23234;

--
-- AUTO_INCREMENT for table `police_stations`
--
ALTER TABLE `police_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=589;

--
-- AUTO_INCREMENT for table `police_stations_draft`
--
ALTER TABLE `police_stations_draft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=711;

--
-- AUTO_INCREMENT for table `violation_categories`
--
ALTER TABLE `violation_categories`
  MODIFY `category_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk2` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk3` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`violation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_2` FOREIGN KEY (`police_station`) REFERENCES `police_stations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `update_driver_profile_requests`
--
ALTER TABLE `update_driver_profile_requests`
  ADD CONSTRAINT `update_driver_profile_requests_ibfk_1` FOREIGN KEY (`id`) REFERENCES `drivers` (`id`);
COMMIT;
