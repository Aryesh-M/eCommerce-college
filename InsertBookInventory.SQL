INSERT INTO `bookstore`.`bookinventory`(`book_title`, `isbn`,`author`, `publish_date`,`price`,`quantity`, `sub_total`, `tax`, `total`) VALUES 
('IMPARABLES II','978-1-7923-9481-2','Morales','2022-01-20','17.07','30','17.07', '2.22','19.29'),
('Powerful Habits','471-2-5521-5512-1','Jacobs','2002-07-11','29.17','42','29.17', '4.67','33.84');

INSERT INTO `bookstore`.`orders`(`user_id`, `sub_total`, `tax`,`total`,`quantity`) VALUES 
('1','17.07','2.22','20.99','3'),
('2','35.99','4.67','42.81','4');

INSERT INTO `bookstore`.`users`(`first_name`, `last_name`, `email`, `address`) VALUES 
('Yash','Marakna','yash.marakna1234@gmail.com','12 Outlook Drive, Kitchener, ON'),
('Rohan','Roshan','rohanroshan@hotmail.com','321 Victoria Street, Vaughan, ON'),
('Ritisha','Katellia','rkatelia@yahoo.com','41-B Peterson Valley, Waterloo, ON'),
('Hally','Johnson','hallyj@gmail.com','110-B Huron Drive, Kitchener, ON');