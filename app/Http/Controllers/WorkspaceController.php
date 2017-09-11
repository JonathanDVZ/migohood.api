<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Crypt;
	use Illuminate\Contracts\Encryption\DecryptException;
	use	Illuminate\Encryption\Encrypter;
	use validator;
	use Aws\S3\S3Client;
	use Aws\Exception\AwsException;
	use Aws\S3\Exception\S3Exception;
	use DateTime;
	use DB;
	use App\Models\Service_Type_Category;
	use App\Models\Service_Category;
	use App\Models\Type_Category;
	use App\Models\Guest;
	use App\Models\Service;
	use App\Models\User;
	use App\Models\Category;
	use App\Models\Languaje;
	use App\Models\Service_Languaje;
	use App\Models\Check_in;
	use App\Models\Price_history_has_duration;
	use App\Models\Duration;
	use App\Models\Payment;
	use App\Models\Service_Payment;
	use App\Models\Price_History;
	use App\Models\Amenite;
	use App\Models\State;
	use App\Models\Check_out;
	use App\Models\City;
	use App\Models\Country;
	use App\Models\Service_Rules;
	use App\Models\Service_Reservation;
	use App\Models\Service_Description;
	use App\Models\SpecialDate;
	use App\Models\Service_Amenite;
	use App\Models\Service_Type;
	use App\Models\Image;
	use App\Models\Type;
	use App\Models\Service_Calendar;
	use App\Models\Service_Accommodation;
	use App\Models\Calendar;
	use App\Models\Availability;
	use App\Models\Emergency_Number;
	use App\Models\Service_Emergency;
	use App\Models\Image_Duration;

	class WorkspaceController extends Controller {
		
	}
