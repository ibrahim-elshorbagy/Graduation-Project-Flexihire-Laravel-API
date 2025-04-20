<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['name' => 'PHP'],
            ['name' => 'JavaScript'],
            ['name' => 'React'],
            ['name' => 'CSS'],
            ['name' => 'Node.js'],
            ['name' => 'Python'],
            ['name' => 'Java'],
            ['name' => 'HTML'],
            ['name' => 'Ruby'],
            ['name' => 'Swift'],
            ['name' => 'C++'],
            ['name' => 'C#'],
            ['name' => 'Go'],
            ['name' => 'TypeScript'],
            ['name' => 'Git'],
            ['name' => 'Docker'],
            ['name' => 'MySQL'],
            ['name' => 'MongoDB'],
            ['name' => 'PostgreSQL'],
            ['name' => 'Firebase'],
            ['name' => 'AWS'],
            ['name' => 'Azure'],
            ['name' => 'Google Cloud'],
            ['name' => 'Docker Compose'],
            ['name' => 'Kubernetes'],
            ['name' => 'GraphQL'],
            ['name' => 'Elasticsearch'],
            ['name' => 'Redis'],
            ['name' => 'Terraform'],
            ['name' => 'Solidity'],
            ['name' => 'Blockchain'],
            ['name' => 'AWS Lambda'],
            ['name' => 'Jenkins'],
            ['name' => 'Angular'],
            ['name' => 'SASS'],
            ['name' => 'Next.js'],
            ['name' => 'Vite'],
            ['name' => 'GraphQL Apollo'],
            ['name' => 'Webpack'],
            ['name' => 'Bash'],
            ['name' => 'Vim'],
            ['name' => 'GitHub'],
            ['name' => 'Apache'],
            ['name' => 'Nginx'],
            ['name' => 'Ruby on Rails'],
            ['name' => 'Stripe'],
            ['name' => 'WordPress'],
            ['name' => 'JQuery'],
            ['name' => 'Flutter'],
            ['name' => 'Xcode'],
            ['name' => 'Apache Kafka'],
            ['name' => 'Figma'],
            ['name' => 'Mongoose'],
        ];

        foreach ($skills as $skill) {
            DB::table('skills')->insert($skill);
        }

           $jobs = [
            'Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'Data Scientist',
            'DevOps Engineer',
            'Product Manager',
            'UI/UX Designer',
            'QA Engineer',
            'Systems Administrator',
            'Project Manager',
            'Technical Support Engineer',
            'Mobile App Developer',
            'Web Developer',
            'Cloud Engineer',
            'Machine Learning Engineer',
            'Database Administrator',
            'Security Analyst',
            'Network Engineer',
            'Business Analyst',
            'AI Engineer',
            'Solutions Architect',
            'Game Developer',
            'Research Scientist',
            'SEO Specialist',
            'Cloud Solutions Architect',
            'Blockchain Developer',
            'Marketing Specialist',
            'Content Writer',
            'Data Analyst',
            'Infrastructure Engineer',
            'Scrum Master',
            'Cloud Consultant',
            'Digital Designer',
            'Enterprise Architect',
            'Business Intelligence Developer',
            'E-commerce Specialist',
            'Web Designer',
            'Agile Coach',
            'Performance Analyst',
            'Customer Success Manager',
            'Technical Writer',
            'Salesforce Developer',
            'Java Developer',
            'PHP Developer',
            'Python Developer',
            'Ruby Developer',
            'C++ Developer',
            'JavaScript Developer',
            'UI Developer',
            'Network Architect',
            'Test Engineer',
            'Site Reliability Engineer'
        ];

        foreach ($jobs as $job) {
            DB::table('our_jobs_title')->insert(['name' => $job]);
        }
    }
}
