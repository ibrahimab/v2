<?php
namespace AppBundle\Tests\Unit\Doctrine\DBAL\Mocks;

use       Doctrine\DBAL\DBALException;
use       Doctrine\DBAL\Platforms\AbstractPlatform;

class MockPlatform extends AbstractPlatform
{
    public function getBlobTypeDeclarationSQL(array $field)
    {
        throw DBALException::notSupported(__METHOD__);
    }
    
    public function getBooleanTypeDeclarationSQL(array $columnDef)
    {
    }
    
    public function getIntegerTypeDeclarationSQL(array $columnDef)
    {
    }
    
    public function getBigIntTypeDeclarationSQL(array $columnDef)
    {
    }
    
    public function getSmallIntTypeDeclarationSQL(array $columnDef)
    {
    }
    
    public function _getCommonIntegerTypeDeclarationSQL(array $columnDef)
    {
    }
    
    public function getVarCharTypeDeclarationSQL(array $columnDef)
    {
        return 'DUMMYVARCHAR';
    }
    
    public function getClobTypeDeclarationSQL(array $columnDef)
    {
        return 'DUMMYCLOB';
    }
    
    public function getJsonTypeDeclarationSQL(array $columnDef)
    {
        return 'DUMMYJSON';
    }
    
    public function getBinaryTypeDeclarationSQL(array $columnDef)
    {
        return 'DUMMYBINARY';
    }
    
    public function getVarcharDefaultLength()
    {
        return 255;
    }
    
    public function getName()
    {
        return 'mock';
    }
    
    protected function initializeDoctrineTypeMappings()
    {
    }
    
    protected function getVarCharTypeDeclarationSQLSnippet($length, $fixed)
    {
    }
}