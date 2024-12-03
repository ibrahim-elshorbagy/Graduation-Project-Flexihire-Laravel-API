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
            ['name' => 'PHP', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919826.png'],
            ['name' => 'JavaScript', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919851.png'],
            ['name' => 'React', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919836.png'],
            ['name' => 'CSS', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919847.png'],
            ['name' => 'Node.js', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919803.png'],
            ['name' => 'Python', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919825.png'],
            ['name' => 'Java', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919845.png'],
            ['name' => 'HTML', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919840.png'],
            ['name' => 'Ruby', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919819.png'],
            ['name' => 'Swift', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919829.png'],
            ['name' => 'C++', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919861.png'],
            ['name' => 'C#', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919837.png'],
            ['name' => 'Go', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919818.png'],
            ['name' => 'TypeScript', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919806.png'],
            ['name' => 'Git', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919810.png'],
            ['name' => 'Docker', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919820.png'],
            ['name' => 'MySQL', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919844.png'],
            ['name' => 'MongoDB', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919842.png'],
            ['name' => 'PostgreSQL', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919850.png'],
            ['name' => 'Firebase', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919848.png'],
            ['name' => 'AWS', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919849.png'],
            ['name' => 'Azure', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919838.png'],
            ['name' => 'Google Cloud', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919841.png'],
            ['name' => 'Docker Compose', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919828.png'],
            ['name' => 'Kubernetes', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919839.png'],
            ['name' => 'GraphQL', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919855.png'],
            ['name' => 'Elasticsearch', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919855.png'],
            ['name' => 'Redis', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919823.png'],
            ['name' => 'Terraform', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919827.png'],
            ['name' => 'Solidity', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919821.png'],
            ['name' => 'Blockchain', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919853.png'],
            ['name' => 'AWS Lambda', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919854.png'],
            ['name' => 'Jenkins', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919852.png'],
            ['name' => 'Angular', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919808.png'],
            ['name' => 'SASS', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919833.png'],
            ['name' => 'Next.js', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919836.png'],
            ['name' => 'Vite', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919809.png'],
            ['name' => 'GraphQL Apollo', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919844.png'],
            ['name' => 'Webpack', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919850.png'],
            ['name' => 'Bash', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919819.png'],
            ['name' => 'Vim', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919825.png'],
            ['name' => 'GitHub', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919836.png'],
            ['name' => 'Apache', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919821.png'],
            ['name' => 'Nginx', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919834.png'],
            ['name' => 'Ruby on Rails', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919837.png'],
            ['name' => 'Stripe', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919832.png'],
            ['name' => 'WordPress', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919818.png'],
            ['name' => 'JQuery', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919827.png'],
            ['name' => 'Flutter', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919846.png'],
            ['name' => 'Xcode', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919847.png'],
            ['name' => 'Apache Kafka', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919849.png'],
            ['name' => 'Figma', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919853.png'],
            ['name' => 'Mongoose', 'icon' => 'https://cdn-icons-png.flaticon.com/512/919/919831.png'],
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
            DB::table('our_jobs')->insert(['name' => $job]);
        }
    }
}
