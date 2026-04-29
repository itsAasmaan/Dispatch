<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('candidate')->get();

        if ($users->isEmpty()) {
            $this->command->warn('No candidate users found. Please run UserSeeder first.');
            return;
        }

        $questions = [
            // DSA Questions
            [
                'user' => $users->random(),
                'title' => 'How to find the median of two sorted arrays?',
                'description' => 'Looking for an efficient solution to find the median of two sorted arrays with combined length of O(log(min(m,n))) complexity.',
                'answer' => 'Use binary search on the smaller array. The key is to partition both arrays such that left elements <= right elements. Time complexity: O(log(min(m,n))).',
                'category' => 'dsa',
                'difficulty' => 'hard',
                'tags' => ['binary-search', 'arrays', 'divide-and-conquer'],
                'companies' => ['Google', 'Amazon', 'Microsoft'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'Implement LRU Cache with O(1) time complexity',
                'description' => 'Need to design and implement a data structure for LRU Cache that supports get and put operations in O(1) time.',
                'answer' => 'Use a hash map for O(1) lookup and a doubly linked list for O(1) insertion/deletion. The hash map stores keys mapped to list nodes.',
                'category' => 'dsa',
                'difficulty' => 'medium',
                'tags' => ['hash-map', 'linked-list', 'design'],
                'companies' => ['Google', 'Amazon', 'Meta'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'Find maximum subarray sum using Kadane\'s algorithm',
                'description' => 'How to implement Kadane\'s algorithm to find the contiguous subarray with the largest sum?',
                'answer' => 'Iterate through the array, keeping track of current sum and maximum sum. Reset current sum to 0 if it becomes negative.',
                'category' => 'dsa',
                'difficulty' => 'easy',
                'tags' => ['arrays', 'dynamic-programming'],
                'companies' => ['Amazon', 'Microsoft'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'Validate Binary Search Tree',
                'description' => 'How to validate if a given binary tree is a valid BST?',
                'answer' => 'Use recursion with min/max bounds. Each node must be within (min, max) range where min and max are updated as we traverse.',
                'category' => 'dsa',
                'difficulty' => 'medium',
                'tags' => ['trees', 'recursion'],
                'companies' => ['Google', 'Amazon', 'Apple'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'Merge K sorted linked lists',
                'description' => 'What is the most efficient way to merge K sorted linked lists?',
                'answer' => 'Use a min-heap (priority queue) to always get the smallest element. Time complexity: O(N log K) where N is total elements.',
                'category' => 'dsa',
                'difficulty' => 'hard',
                'tags' => ['linked-list', 'heap', 'merge-sort'],
                'companies' => ['Amazon', 'Google'],
                'is_approved' => true,
            ],
            // System Design Questions
            [
                'user' => $users->random(),
                'title' => 'Design a URL shortening service like bit.ly',
                'description' => 'How would you design a URL shortening service? Consider scalability, storage, and collision handling.',
                'answer' => 'Use base62 encoding for short URLs. Store mapping in database with hash index. Use consistent hashing for distribution. Consider cache for hot URLs.',
                'category' => 'system_design',
                'difficulty' => 'medium',
                'tags' => ['url-shortener', 'database', 'cache'],
                'companies' => ['Google', 'Amazon'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'Design a distributed cache system',
                'description' => 'How would you design a distributed caching system like Redis?',
                'answer' => 'Use consistent hashing for data partitioning. Implement replication for fault tolerance. Consider LRU eviction policy and TTL management.',
                'category' => 'system_design',
                'difficulty' => 'hard',
                'tags' => ['cache', 'distributed-systems', 'consistency'],
                'companies' => ['Amazon', 'Meta'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'Design a rate limiter system',
                'description' => 'How would you implement a rate limiting system for an API?',
                'answer' => 'Use token bucket or sliding window algorithm. Store counters in Redis with TTL. Consider distributed locking for accuracy.',
                'category' => 'system_design',
                'difficulty' => 'medium',
                'tags' => ['rate-limiting', 'api', 'redis'],
                'companies' => ['Google', 'Amazon', 'Meta'],
                'is_approved' => true,
            ],
            // Behavioural Questions
            [
                'user' => $users->random(),
                'title' => 'Tell me about a time you failed and what you learned',
                'description' => 'Common behavioral question. How do you structure your answer using STAR method?',
                'answer' => 'Choose a real failure with clear lessons. Explain the situation, your actions, the outcome, and specifically what you learned and how you improved.',
                'category' => 'behavioural',
                'difficulty' => 'easy',
                'tags' => ['star-method', 'leadership'],
                'companies' => ['Amazon', 'Google', 'Meta'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'How do you handle conflict with a team member?',
                'description' => 'Behavioral question about conflict resolution in a team setting.',
                'answer' => 'Focus on communication and understanding. Explain how you listened to their perspective, found common ground, and reached a resolution.',
                'category' => 'behavioural',
                'difficulty' => 'easy',
                'tags' => ['conflict-resolution', 'teamwork'],
                'companies' => ['All'],
                'is_approved' => true,
            ],
            // Frontend Questions
            [
                'user' => $users->random(),
                'title' => 'Explain the React virtual DOM and how it works',
                'description' => 'What is the virtual DOM and how does React use it for efficient updates?',
                'answer' => 'Virtual DOM is a lightweight copy of the real DOM. React compares the new virtual DOM with the previous one (diffing) and only updates what changed in the real DOM (reconciliation).',
                'category' => 'frontend',
                'difficulty' => 'medium',
                'tags' => ['react', 'virtual-dom', 'performance'],
                'companies' => ['Meta', 'Amazon'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'What is the difference between useEffect and useLayoutEffect?',
                'description' => 'When should you use useLayoutEffect over useEffect in React?',
                'answer' => 'useLayoutEffect fires synchronously after DOM mutations but before paint. Use it when you need to measure DOM layout or prevent visual flicker. useEffect is async and fires after paint.',
                'category' => 'frontend',
                'difficulty' => 'medium',
                'tags' => ['react', 'hooks', 'useeffect'],
                'companies' => ['Meta', 'Google'],
                'is_approved' => true,
            ],
            // Backend Questions
            [
                'user' => $users->random(),
                'title' => 'Explain the difference between SQL and NoSQL databases',
                'description' => 'When would you choose SQL over NoSQL or vice versa?',
                'answer' => 'SQL: structured data, complex queries, transactions, strict schema. NoSQL: flexible schema, horizontal scaling, high throughput, eventual consistency.',
                'category' => 'backend',
                'difficulty' => 'easy',
                'tags' => ['database', 'sql', 'nosql'],
                'companies' => ['Amazon', 'Microsoft'],
                'is_approved' => true,
            ],
            [
                'user' => $users->random(),
                'title' => 'What are the SOLID principles in object-oriented design?',
                'description' => 'Explain each of the SOLID principles with examples.',
                'answer' => 'S: Single Responsibility - one class, one reason to change. O: Open/Closed - open for extension, closed for modification. L: Liskov Substitution - subtypes must be substitutable. I: Interface Segregation - specific interfaces over general ones. D: Dependency Inversion - depend on abstractions, not concretions.',
                'category' => 'backend',
                'difficulty' => 'medium',
                'tags' => ['oop', 'design-patterns', 'solid'],
                'companies' => ['Google', 'Amazon'],
                'is_approved' => true,
            ],
            // DevOps Questions
            [
                'user' => $users->random(),
                'title' => 'How does Kubernetes handle pod failures?',
                'description' => 'Explain Kubernetes self-healing mechanisms and controllers.',
                'answer' => 'Kubernetes uses various controllers: Deployment ensures desired replicas, ReplicaSet maintains pod count, kubelet restarts failed containers, liveness probes detect issues.',
                'category' => 'devops',
                'difficulty' => 'medium',
                'tags' => ['kubernetes', 'containers', 'orchestration'],
                'companies' => ['Google', 'Amazon'],
                'is_approved' => true,
            ],
        ];

        foreach ($questions as $questionData) {
            $user = $questionData['user'];
            unset($questionData['user']);

            Question::firstOrCreate(
                ['title' => $questionData['title']],
                array_merge($questionData, [
                    'user_id' => $user->id,
                    'upvote_count' => rand(5, 100),
                    'view_count' => rand(50, 500),
                ])
            );
        }

        $this->command->info('Questions seeded: ' . count($questions) . ' questions.');
    }
}