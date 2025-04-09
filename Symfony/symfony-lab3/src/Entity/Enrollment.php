<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Enrollment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    private $student;

    #[ORM\ManyToOne(targetEntity: Course::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $course;

    #[ORM\Column(type: 'string', length: 50)]
    private $semester;

    public function getId(): ?int { return $this->id; }
    public function getStudent(): ?Student { return $this->student; }
    public function setStudent(?Student $student): self { $this->student = $student; return $this; }
    public function getCourse(): ?Course { return $this->course; }
    public function setCourse(?Course $course): self { $this->course = $course; return $this; }
    public function getSemester(): ?string { return $this->semester; }
    public function setSemester(string $semester): self { $this->semester = $semester; return $this; }
}