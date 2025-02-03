<?php

namespace Database\Factories;

use App\Models\JobList;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobListFactory extends Factory
{
    protected $model = JobList::class;

    public function definition(): array
    {
        // Array of sample skills
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Python',
            'Java', 'SQL', 'AWS', 'Docker', 'Git', 'Node.js'
        ];

        // Array of sample locations
        $locations = [
            'New York, NY', 'San Francisco, CA', 'London, UK',
            'Toronto, CA', 'Sydney, AU', 'Berlin, DE'
        ];

        return [
            'title' => $this->faker->jobTitle(),
            'location' => $this->faker->randomElement($locations),
            'description' => $this->faker->paragraphs(3, true),
            'skills' => json_encode($this->faker->randomElements($skills, 4)),
            'date_posted' => $this->faker->date(),
        ];
    }
}
