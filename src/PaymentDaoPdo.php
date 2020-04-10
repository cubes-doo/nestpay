<?php

namespace Cubes\Nestpay;

class PaymentDaoPdo implements PaymentDao 
{
    /**
     * @var \PDO
     */
    protected $pdo;

    protected $tableName = 'nestpay_payments';
    
    public function __construct(\PDO $pdo, $tableName = null)
    {
        if ($pdo) {
            $this->setPdo($pdo); 
        }

        if ($tableName) {
            $this->setTableName($tableName);
        }
    }

    /**
     * @param \PDO $pdo
     * @return PaymentDaoPdo
     */
    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;

        return $this;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        if (!($this->pdo instanceof \PDO)) {
            throw new \RuntimeException('Trying to get uninitialized property pdo');
        }

        return $this->pdo;
    }

    /**
     * The table name for transactions
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return PaymentDaoPdo
     */
    public function setTableName($tableName)
    {
        if (!is_string($tableName) || empty($tableName)) {
            throw new \InvalidArgumentException('Argument $tableName must be non empty string');
        }

        return $this;
    }

	/**
	 * Fetch payment by $oid
	 * 
	 * @return \Cubes\Nestpay\Payment
	 * @param scalar $oid
	 */
    public function getPayment($oid)
    {
        $pdo = $this->getPdo();

        $statement = $pdo->prepare('SELECT * FROM `' . $this->getTableName() . '` WHERE `oid` = :oid');

        $statement->execute([
            ':oid' => $oid
        ]);


        $properties = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$properties) {
            return null;
        }

        return new PaymentStandard($properties);
    }
	
	/**
	 * Saves the payment
	 * 
	 * @param \Cubes\Nestpay\Payment $payment
	 * @return \Cubes\Nestpay\Payment
	 */
    public function savePayment(Payment $payment)
    {
        $existingPayment = $this->getPayment($payment->getOid());
        if (!$existingPayment) {
            return $this->createPayment($payment->getProperties());
        }

        $pdo = $this->getPdo();

        $properties = $payment->getProperties();
        $properties['updated_at'] = date('Y-m-d H:i:s');

        $sql = 'UPDATE `' . $this->getTableName() . '` SET ';

        $setPart = [];

        foreach ($properties as $key => $value) {
            $setPart[] = '`' . $key . '` = ' . $pdo->quote($value);
        }

        $sql .= implode(',', $setPart);

        $sql .= ' WHERE `oid` = ' . $pdo->quote($properties['oid']);
        
        $statement = $pdo->prepare($sql);

        $statement->execute();

        return $payment;
    }

	/**
	 * Creates new payment
	 *
	 * @param array $properties
	 * @return \Cubes\Nestpay\Payment
	 */
    public function createPayment(array $properties)
    {
        if (!isset($properties[Payment::PROP_OID])) {
            $properties[Payment::PROP_OID] = PaymentStandard::generateOid();
        }

        if (!isset($properties[Payment::PROP_TRANTYPE])) {
            $properties[Payment::PROP_TRANTYPE] = Payment::TRAN_TYPE_AUTH;
        }

        foreach ($properties as $key => $value) {
            if (!in_array($key, Payment::ALLOWED_PROPERTIES)) {
                unset($properties[$key]);
            } 
        }

        $properties['created_at'] = date('Y-m-d H:i:s');
        $properties['updated_at'] = date('Y-m-d H:i:s');

        $pdo = $this->getPdo();

        $sql = 'INSERT INTO `' . $this->getTableName() . '`';

        $columnsPart = [];
        $valuesPart = [];
        foreach ($properties as $key => $value) {
            $columnsPart[] = '`' . $key . '`';
            $valuesPart[] = $pdo->quote($value);
        }

        $sql .= ' (' . implode(',', $columnsPart) . ')';

        $sql .= ' VALUES (' . implode(',', $valuesPart) . ')';

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $properties['id'] = $pdo->lastInsertId();
        
        $payment = new PaymentStandard($properties);
        
        return $payment;
    }
}
