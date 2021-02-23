<?php

use Illuminate\Database\Seeder;
use App\SecurityQuestion;
class SecurityQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SecurityQuestion::create([
            'question_id'=>'1',
            'name'=>'What is the first name and last name of your first boyfriend or girlfriend?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'2',
            'name'=>'Which phone number do you remember most from your childhood?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'3',
            'name'=>'Which phone number do you remember most from your childhood?'
        ]);
        
        SecurityQuestion::create([
            'question_id'=>'4',
            'name'=>'What was your favorite place to visit as a child?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'5',
            'name'=>'Who is your favorite actor, musician, or artist?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'6',
            'name'=>'What is the name of your favorite pet?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'7',
            'name'=>'In what city were you born?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'8',
            'name'=>'What High School did you attend?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'9',
            'name'=>'What is the name of your first school?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'10',
            'name'=>'What is your favorite movie?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'11',
            "name"=>"What is your mother's maiden name?"
        ]);

        SecurityQuestion::create([
            'question_id'=>'12',
            'name'=>'What street did you grew on?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'13',
            'name'=>'What was the make of your first car?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'14',
            'name'=>'When is your anniversary?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'15',
            'name'=>'What is your favorite color?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'16',
            "name"=>"What is your father's middle name?"
        ]);

        SecurityQuestion::create([
            'question_id'=>'17',
            'name'=>'What is the name of your first grade teacher?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'18',
            'name'=>'What was your highschool mascot?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'19',
            'name'=>'What is your favorite web browser?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'20',
            'name'=>'What is your favorite website?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'21',
            'name'=>'What is your favorite forum?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'22',
            'name'=>'What is your favorite online platform?'
        ]);

        SecurityQuestion::create([
            'question_id'=>'23',
            'name'=>'What is your favorite social media website?'
        ]);

    }
}
