<? 
namespace Application\test;

//./vendor/bin/phpunit -c ./application/unit/
    use Application\DB;
    use Entity\Config;
    use Application\Bootstrap;
    
    class DBTest extends \PHPUnit_Framework_TestCase
    {
        protected function setUp()
        {
            define('SERVER_SCRIPT', 'unittest');
            \Slim\Environment::mock();
            \Application\Bootstrap::init();
            \Library\SFacebook::init();
        }
        
        public function testGetEm()
        {
            $em = DB::em();
            $this->assertInstanceOf('\Doctrine\ORM\EntityManager', $em,'DB::em() should be instance of \Doctrine\ORM\EntityManager');
        }
    }

?>