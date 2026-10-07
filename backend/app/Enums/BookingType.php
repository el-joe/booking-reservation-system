<?php

declare(strict_types=1);

namespace App\Enums;

enum BookingType: string
{
    // Accommodation
    case Hotel = 'hotel';
    case Apartment = 'apartment';
    case Hostel = 'hostel';
    case Villa = 'villa';
    case Resort = 'resort';
    case Camping = 'camping';

    // Travel & Transport
    case Flight = 'flight';
    case Train = 'train';
    case CarRental = 'car_rental';
    case Yacht = 'yacht';
    case Cruise = 'cruise';
    case AirportTransfer = 'airport_transfer';

    // Dining & Events
    case Restaurant = 'restaurant';
    case EventHall = 'event_hall';
    case Catering = 'catering';
    case FoodPreorder = 'food_preorder';

    // Services & Appointments
    case Medical = 'medical';
    case SalonSpa = 'salon_spa';
    case HomeService = 'home_service';
    case Fitness = 'fitness';
    case Tutoring = 'tutoring';
    case Photography = 'photography';

    // Entertainment & Activities
    case EventTicket = 'event_ticket';
    case Tour = 'tour';
    case EscapeRoom = 'escape_room';
    case Coworking = 'coworking';
    case SportsCourt = 'sports_court';

    // Business & Resources
    case MeetingRoom = 'meeting_room';
    case EquipmentRental = 'equipment_rental';
    case Parking = 'parking';
    case StorageUnit = 'storage_unit';

    // Online / Virtual
    case OnlineConsultation = 'online_consultation';
    case Webinar = 'webinar';
    case LiveStreaming = 'live_streaming';

    public function label(): string
    {
        return match ($this) {
            self::Hotel => 'Hotel',
            self::Apartment => 'Apartment',
            self::Hostel => 'Hostel',
            self::Villa => 'Villa',
            self::Resort => 'Resort',
            self::Camping => 'Camping',
            self::Flight => 'Flight',
            self::Train => 'Train',
            self::CarRental => 'Car Rental',
            self::Yacht => 'Yacht',
            self::Cruise => 'Cruise',
            self::AirportTransfer => 'Airport Transfer',
            self::Restaurant => 'Restaurant',
            self::EventHall => 'Event Hall',
            self::Catering => 'Catering',
            self::FoodPreorder => 'Food Pre-order',
            self::Medical => 'Medical',
            self::SalonSpa => 'Salon & Spa',
            self::HomeService => 'Home Service',
            self::Fitness => 'Fitness',
            self::Tutoring => 'Tutoring',
            self::Photography => 'Photography',
            self::EventTicket => 'Event Ticket',
            self::Tour => 'Tour',
            self::EscapeRoom => 'Escape Room',
            self::Coworking => 'Co-working',
            self::SportsCourt => 'Sports Court',
            self::MeetingRoom => 'Meeting Room',
            self::EquipmentRental => 'Equipment Rental',
            self::Parking => 'Parking',
            self::StorageUnit => 'Storage Unit',
            self::OnlineConsultation => 'Online Consultation',
            self::Webinar => 'Webinar',
            self::LiveStreaming => 'Live Streaming',
        };
    }

    public function color(): string
    {
        return match ($this->category()) {
            'accommodation' => 'blue',
            'travel' => 'cyan',
            'dining' => 'orange',
            'services' => 'pink',
            'entertainment' => 'purple',
            'business' => 'indigo',
            'online' => 'teal',
            default => 'gray',
        };
    }

    public function category(): string
    {
        return match ($this) {
            self::Hotel,
            self::Apartment,
            self::Hostel,
            self::Villa,
            self::Resort,
            self::Camping => 'accommodation',

            self::Flight,
            self::Train,
            self::CarRental,
            self::Yacht,
            self::Cruise,
            self::AirportTransfer => 'travel',

            self::Restaurant,
            self::EventHall,
            self::Catering,
            self::FoodPreorder => 'dining',

            self::Medical,
            self::SalonSpa,
            self::HomeService,
            self::Fitness,
            self::Tutoring,
            self::Photography => 'services',

            self::EventTicket,
            self::Tour,
            self::EscapeRoom,
            self::Coworking,
            self::SportsCourt => 'entertainment',

            self::MeetingRoom,
            self::EquipmentRental,
            self::Parking,
            self::StorageUnit => 'business',

            self::OnlineConsultation,
            self::Webinar,
            self::LiveStreaming => 'online',
        };
    }
}
