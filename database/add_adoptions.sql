-- Insert sample adoption applications
INSERT INTO adoption_applications (
    animal_id, user_id, status, applicant_name, applicant_email, 
    applicant_phone, home_address, housing_type, has_yard, 
    other_pets, experience, reason_for_adoption
) VALUES
-- John's pending application for Luna
(2, 2, 'pending', 'John Doe', 'john@example.com',
'555-0123', '123 Main St, Anytown, ST 12345', 'house', true,
'One older cat, very friendly', '5 years experience with cats',
'Looking for a companion for my current cat and fell in love with Luna''s gentle personality.'),

-- Jane's approved application for Rocky
(3, 3, 'approved', 'Jane Smith', 'jane@example.com',
'555-0456', '456 Oak Ave, Somewhere, ST 12345', 'house', true,
'No current pets', 'Grew up with German Shepherds',
'I have a large fenced yard and lots of time for exercise and training.'),

-- John's rejected application for Max
(1, 2, 'rejected', 'John Doe', 'john@example.com',
'555-0123', '123 Main St, Anytown, ST 12345', 'apartment', false,
'One cat', 'First-time dog owner',
'Would love to have a dog to go running with.'),

-- Jane's pending application for Charlie
(5, 3, 'pending', 'Jane Smith', 'jane@example.com',
'555-0456', '456 Oak Ave, Somewhere, ST 12345', 'house', true,
'No current pets', 'Previous experience with Beagles',
'Looking for an energetic companion for daily walks and playtime.'); 