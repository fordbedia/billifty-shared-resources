<?php
namespace BilliftySDK\SharedResources\SDK\Console\Helpers\Traits;

use BilliftySDK\SharedResources\SDK\Foundation\Enums\StubsPathEnum;

trait ResolveStubPath
{
    /**
     * @param string $stub
     * @return string
     */
    public function resolveMigrationStubPath(string $stub): string
    {
        $published = base_path('stubs/' . $stub);

        return file_exists($published)
            ? $published
            : base_path(StubsPathEnum::MIGRATION->getFullPath() . $stub);
    }

    /**
     * @param string $stub
     * @return string
     */
    public function resolveControllerStubPath(string $stub): string
    {
        return base_path(StubsPathEnum::CONTROLLER->getFullPath() . $stub);
    }

    /**
     * @param string $stub
     * @return string
     */
    public function resolveModelStubPath(string $stub): string
    {
        return base_path(StubsPathEnum::MODEL->getFullPath() . $stub);
    }

    /**
     * @param string $stub
     * @return string
     */
    public function resolveFactoryStubPath(string $stub): string
    {
        return base_path(StubsPathEnum::FACTORY->getFullPath() . $stub);
    }

    public function resolveSeederStubPath(string $stub): string
    {
        return base_path(StubsPathEnum::SEEDER->getFullPath() . $stub);
    }

		public function resolveResourceStubPath(string $stub): string
		{
			return base_path(StubsPathEnum::RESOURCE->getFullPath() . $stub);
		}
}
