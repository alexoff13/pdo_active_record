<?php

namespace Worker;

use PDO;

class Worker extends ActiveRecord
{
    private ?int $id;
    private string $name;
    private string $address;
    private string $salary;

    /**
     * @param string $name
     * @param string $address
     * @param string $salary
     */
    public function __construct(string $name, string $address, string $salary, int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->salary = $salary;
    }

    public static function remove($id): bool
    {
        // если запись найдена, удаляем
        if (Worker::getById($id)) {
            self::connect();
            $connect = self::getConnection();

            $insertQuery = 'DELETE FROM workers.workers WHERE id=?';
            $query = $connect->prepare($insertQuery);
            $query->execute([$id]);

            self::unsetConnect();
            return true;
        }
        return false;
    }

    public static function getByID($id)
    {
        self::connect();
        $connect = self::getConnection();

        $insertQuery = 'SELECT * FROM workers.workers WHERE id=?';
        $query = $connect->prepare($insertQuery);
        $query->execute([$id]);
        $foundWorker = $query->fetch();

        self::unsetConnect();

        if ($foundWorker == false)
            return false;
        else return new Worker($foundWorker['name'],
            $foundWorker['address'], $foundWorker['salary'], $foundWorker['id']);
    }

    public static function getAll(): array
    {
        self::connect();
        $connect = self::getConnection();

        $workers = array();

        // получаем наши таски
        foreach ($connect->query('select * from workers.workers order by id') as $row) {
            $worker = new Worker(
                (string)$row['name'],
                (string)$row['address'],
                (int)$row['salary'],
                (int)$row['id']);

            $workers[] = $worker;
        }

        self::unsetConnect();
        return $workers;
    }

    public function save(): bool
    {
        $workerByFields = Worker::getByFields($this->name, $this->address);

        if ($workerByFields->name != $this->name && $workerByFields->address != $this->address) {
            self::connect();
            $connect = self::getConnection();

            $insertQuery = 'UPDATE workers.workers set name=:name,address=:address, salary=:salary WHERE id=:id';
            $query = $connect->prepare($insertQuery);
            $query->execute(['name' => $this->name, 'address' => $this->address,
                'salary' => $this->salary, 'id' => $this->id]);

            self::unsetConnect();
            return true;
        } else return false;
    }

    public static function getByFields($name, $address)
    {
        self::connect();
        $connect = self::getConnection();

        $selectQuery = 'select * from workers.workers where name=:name and address=:address';
        $selectQuery = $connect->prepare($selectQuery);
        $selectQuery->execute(['name' => $name, 'address' => $address]);
        $foundWorker = $selectQuery->fetch();

        self::unsetConnect();

        if ($foundWorker == false)
            return false;
        else {
            return new Worker($foundWorker['name'],
                $foundWorker['address'], $foundWorker['salary'], $foundWorker['id']);

        }
    }

    /**
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        return self::$connection;
    }

    public function add(): bool
    {
        // Если такой записи нет, добавляем
        $foundTask = self::getByFields($this->name, $this->address);
        if (!$foundTask) {
            self::connect();
            $connect = self::getConnection();

            $insertQuery = 'INSERT INTO workers.workers(name,address,salary) VALUES(:name,:address,:salary)';
            $query = $connect->prepare($insertQuery);
            $query->execute(['name' => $this->name, 'address' => $this->address, 'salary' => $this->salary]);

            self::unsetConnect();
            return true;
        } else return false;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getSalary(): string
    {
        return $this->salary;
    }

    /**
     * @param string $salary
     */
    public function setSalary(string $salary): void
    {
        $this->salary = $salary;
    }

}